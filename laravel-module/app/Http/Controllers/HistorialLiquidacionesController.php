<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\LiqHistorialLiquidaciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
                'archivo' => 'required|file'
            ],
            [
                'id.required' => 'Debe de seleccionar a un usuario',
                'fecha.required' => 'Debe de seleccionar una fecha',
                'archivo.file' => 'Lo seleccionado debe ser un archivo.'
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
            ->get();
        if (!$busqueda->isEmpty()) {
            return ['estado' => 'success', 'liquidaciones' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado liquidaciones con el empleado solicitado'];
        }
    }
}
