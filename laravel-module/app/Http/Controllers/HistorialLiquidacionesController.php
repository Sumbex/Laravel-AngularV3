<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\LiqHistorialLiquidaciones;
use App\Liquidaciones;
use App\LiqHistorialConfigHaberes;
use App\LiqHistorialConfigDescuentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HistorialLiquidacionesController extends Controller
{
    // VALIDADOR
    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'fecha' => 'required',
                'archivo' => 'required|mimes:pdf'
            ],
            [
                'id.required' => 'Debe de seleccionar a un usuario',
                'fecha.required' => 'Debe de seleccionar una fecha',
                'archivo.required' => 'Debe de seleccionar un archivo.',
                'archivo.mimes' => 'Lo seleccionado debe ser un archivo PDF.'
            ]
        );


        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    // FUNCION PARA GUARDAR ARCHIVO
    public function guardarArchivo($archivo, $ruta)
    {
        $filenameext = $archivo->getClientOriginalName();
        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = $filename . '_' . time() . '.' . $extension;
        $rutaDB = 'storage/' . $ruta . $nombreArchivo;

        $guardar = Storage::put($ruta . $nombreArchivo, (string) file_get_contents($archivo), 'public');

        if ($guardar) {
            return ['estado' =>  'success', 'archivo' => $rutaDB];
        } else {
            return ['estado' =>  'failed', 'mensaje' => 'Error al intentar guardar el archivo.'];
        }
    }

    // GUARDAR INGRESO
    public function setDatosLiquidacion(Request $request)
    {
        $validarDatos = $this->validarDatos($request);
        if ($validarDatos['estado'] == 'success') {
                $liquidacion = new LiqHistorialLiquidaciones;
                $liquidacion->fecha = $request->fecha;
                $liquidacion->id_empleado = $request->id;
                $guardarArchivo = $this->guardarArchivo($request->archivo, 'ArchivosLiquidaciones/');
                if ($guardarArchivo['estado'] == "success") {
                    $liquidacion->ruta_archivo = $guardarArchivo['archivo'];
                } else {
                    return $guardarArchivo;
                }
                $liquidacion->id_usuario = Auth::user()->id;
                $liquidacion->activo = "S";
                if ($liquidacion->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Liquidación ingresada correctamente.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Ha ocurrido un error al ingresar la liquidación, intentar nuevamente.'];
                }
        } else {
            return $validarDatos;
        }
    }

    // ACTUALIZADOR LIQUIDACIONES
    protected function actualizarLiquidacion(Request $request)
    {
        switch ($request->campo) {
            case 'fecha':
                $liquidacion = LiqHistorialLiquidaciones::find($request->id);
                if (!is_null($liquidacion)) {
                    $liquidacion->fecha = $request->input;
                    if ($liquidacion->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Fecha actualizada correctamente.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Archivo no encontrado.'];
                }
                break;

            case 'archivo':
                    $liquidacion = LiqHistorialLiquidaciones::find($request->id);
                    if (!is_null($liquidacion)) {
                        $ruta = substr($liquidacion->ruta_archivo, 8);
                        $borrar = Storage::delete($ruta);
                        if ($borrar) {
                            $guardarArchivo = $this->guardarArchivo($request->input, 'ArchivosLiquidaciones/');
                            if ($guardarArchivo['estado'] == "success") {
                                $liquidacion->ruta_archivo = $guardarArchivo['archivo'];
                                if ($liquidacion->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Archivo actualizado correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                            } else {
                                return $guardarArchivo;
                            }
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Archivo no encontrado.'];
                    }
                break;
        }
    }

    // OBTENER LIQUIDACIONES POR ID EMPLEADO
    public function getLiquidacionesPorId($id)
    {
        $busqueda = DB::table('liq_historial_liquidaciones as l')
            ->select('l.*', 'e.nombre_trabajador as nombre')
            ->join('liq_empleado as e', 'e.id', '=', 'l.id_empleado')
            ->where('l.id_empleado', $id)
            ->orderBy('fecha', 'desc')
            ->get();
        if (!$busqueda->isEmpty()) {
            foreach ($busqueda as $key) {
                setlocale(LC_TIME, 'es');
                $key->fecha = Carbon::parse($key->fecha)->formatLocalized('%d %B del %Y');
            }
            return ['estado' => 'success', 'liquidaciones' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado liquidaciones con el empleado solicitado'];
        }
    }

    // RUTAS PARA LE GENERACION DE LIQUIDACIONES DE SUELDO
    public function getConfiguracionHaberesPorIdEmpleado($id)
    {
        $busqueda = DB::table('liq_config_haberes as h')
            ->select('h.*', 'lh.descripcion')
            ->join('cs_lista_haberes as lh', 'lh.id', '=', 'h.cs_lista_haberes_id')
            ->where('h.empleado_id', $id)
            ->where('h.activo', 'S')
            ->get();
        if (!$busqueda->isEmpty()) {
            return ['estado' => 'success', 'haberes' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado haberes con el empleado solicitado'];
        }
    }

    public function getConfiguracionDescuentosPorIdEmpleado($id)
    {
        $busqueda = DB::table('liq_config_descuentos as d')
            ->select('d.*', 'ld.descripcion')
            ->join('cs_lista_descuentos as ld', 'ld.id', '=', 'd.cs_lista_descuentos_id')
            ->where('d.empleado_id', $id)
            ->where('d.activo', 'S')
            ->get();
        if (!$busqueda->isEmpty()) {
            return ['estado' => 'success', 'descuentos' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado descuentos con el empleado solicitado'];
        }
    }

    // FUNCIONES PARA GUARDAR EL HISTORIAL DE LIQUIDACIONES
    public function setHistorialLiquidacion(Request $request){
            DB::beginTransaction();
            $liquidacion = new Liquidaciones;
            $liquidacion->empleado_id = $request->id;
            $liquidacion->fecha = $request->fecha;
            $liquidacion->activo = 'S';
            if ($liquidacion->save()) {
                $haberes = $this->ingresarHaberes($request->confHaberes, $liquidacion->id);
                $descuentos = $this->ingresarDescuentos($request->confDescuentos, $liquidacion->id);
                if ($haberes == true && $descuentos == true) {
                    DB::commit();
                    return ['estado' => 'success', 'mensaje' => 'Liquidación Generada Correctamente'];
                } else {
                    DB::rollBack();
                    return ['estado' => 'failed', 'mensaje' => 'No se ha logrado generar la liquidación, intente nuevamente'];
                }
            }
    }

    protected function ingresarHaberes($arrayHaberes, $liq_id)
    {
        $count = 0;
        DB::beginTransaction();
        foreach ($arrayHaberes as $key) {
            $historialHaberes = new LiqHistorialConfigHaberes;
            $historialHaberes->liquidacion_id = $liq_id;
            $historialHaberes->cs_lista_haberes_id = $key['cs_lista_haberes_id'];
            $historialHaberes->porcentaje = $key['porcentaje'];
            $historialHaberes->monto = $key['monto'];
            $historialHaberes->dias = $key['dias'];
            $historialHaberes->cargas = $key['cargas'];
            $historialHaberes->horas = $key['horas'];
            $historialHaberes->activo = 'S';
            if ($historialHaberes->save()) {
                $count++;
            }
        }
        if (count($arrayHaberes) == $count) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
            return false;
        }
    }

    protected function ingresarDescuentos($arrayDescuentos, $liq_id)
    {
        $count = 0;
        DB::beginTransaction();
        foreach ($arrayDescuentos as $key) {
            $historialDescuentos = new LiqHistorialConfigDescuentos;
            $historialDescuentos->liquidacion_id = $liq_id;
            $historialDescuentos->cs_lista_descuentos_id = $key['cs_lista_descuentos_id'];
            $historialDescuentos->porcentaje = $key['porcentaje'];
            $historialDescuentos->monto = $key['monto'];
            $historialDescuentos->activo = 'S';
            if ($historialDescuentos->save()) {
                $count++;
            }
        }
        if (count($arrayDescuentos) == $count) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
            return false;
        }
    }

    // Variable para traer las liquidaciones generadas
    public function getLiquidacionesGeneradasPorIdEmpleado($id)
    {
        $busqueda = DB::table('liquidacion as l')
            ->select('l.*', 'e.nombre_trabajador')
            ->join('liq_empleado as e', 'e.id', '=', 'l.empleado_id')
            ->where('l.empleado_id', $id)
            ->where('l.activo', 'S')
            ->orderBy('fecha', 'desc')
            ->get();
        if (!$busqueda->isEmpty()) {
            foreach ($busqueda as $key) {
                setlocale(LC_TIME, 'es');
                $key->fecha = Carbon::parse($key->fecha)->formatLocalized('%d %B del %Y');
            }
            return ['estado' => 'success', 'liquidaciones' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado liquidaciones con el empleado solicitado'];
        }
    }

    // Traer la liquidación generada por od
    public function getHaberesPorIdLiquidacion($id)
    {
        $busqueda = DB::table('liq_hist_haberes as h')
            ->select('h.*', 'lh.descripcion')
            ->join('cs_lista_haberes as lh', 'lh.id', '=', 'h.cs_lista_haberes_id')
            ->where('h.liquidacion_id', $id)
            ->where('h.activo', 'S')
            ->get();
        if (!$busqueda->isEmpty()) {
            return ['estado' => 'success', 'haberes' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado haberes con el empleado solicitado'];
        }
    }

    public function getDescuentosPorIdLiquidacion($id)
    {
        $busqueda = DB::table('liq_hist_descuentos as d')
            ->select('d.*', 'ld.descripcion')
            ->join('cs_lista_descuentos as ld', 'ld.id', '=', 'd.cs_lista_descuentos_id')
            ->where('d.liquidacion_id', $id)
            ->where('d.activo', 'S')
            ->get();
        if (!$busqueda->isEmpty()) {
            return ['estado' => 'success', 'descuentos' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado descuentos con el empleado solicitado'];
        }
    }
}
