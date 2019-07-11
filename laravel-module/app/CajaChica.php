<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CuentaSindicatoController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CajaChica extends Model
{
    protected  $saldo = 100000;

    protected $table = "cs_caja_chica";

    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'archivo_documento' => 'required|file|mimes:pdf',
                'numero_documento' => 'required|unique:cs_caja_chica,numero_documento',
                'descripcion' => 'required|min:0',
                'definicion' => 'required|min:0',
                'monto' => 'required|integer|min:1|max:100000'

            ],
            [
                'fecha.required' => 'La fecha es necesaria',
                'archivo_documento.required' => 'Debe seleccionar un archivo',
                'archivo_documento.file' => 'Lo seleccionado debe ser un archivo',
                'archivo_documento.mimes' => 'El archivo debe ser extension PDF',
                'numero_documento.required' => 'El numero de documento es necesario',
                'numero_documento.unique' => 'El numero de documento ya existe en tus registros',
                'descripcion.required' => 'La descripcion es necesaria',
                'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
                'monto.required' => 'El monto es necesario',
                'monto.integer' => 'Debe ingresar solo numeros',
                'monto.min' => 'El monto debe ser mayor a 0.',
                'monto.max' => 'El monto no debe ser mayor a 100000 pesos.'
            ]
        );

        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function div_fecha($value)
    {
        $fecha = $value;
        $ano = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $ano, 'mes' => $mes, 'dia' => $dia
        ];
    }
    protected function anio_tipo_id($value)
    {
        return DB::table('anio')->where('descripcion', $value)->first();
    }

    protected function mes_tipo_id($value)
    {
        return DB::table('mes')->where('id', $value)->first();
    }

    protected function saldoActualCaja($anio, $mes)
    {
        $caja = $this->traerCajaChica($anio, $mes);
        $tomar = true;

        if (!$caja->isEmpty()) {

            $totales = $this->totalIngEgre($anio, $mes);

            for ($i = 0; $i < count($caja); $i++) {
                switch ($caja[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $caja[$i]->saldo_actual = $this->saldo + $caja[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $caja[$i]->saldo_actual = $caja[$i - 1]->saldo_actual + $caja[$i]->monto_ingreso;
                        }
                        break;
                    case 2:
                        if ($tomar == true) {
                            $caja[$i]->saldo_actual = $this->saldo - $caja[$i]->monto_egreso;
                            $tomar = false;
                        } else {
                            $caja[$i]->saldo_actual = $caja[$i - 1]->saldo_actual - $caja[$i]->monto_egreso;
                        }
                        break;
                }
            }
            return ['caja' => $caja, 'totales' => $totales];
        } else {
            return ['estado' =>  'failed', 'mensaje' => 'no hay ingresos en Caja Chica'];
        }
    }

    protected function guardarArchivo($archivo, $ruta)
    {
        $filenameext = $archivo->getClientOriginalName();
        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = $filename . '_' . time() . '.' . $extension;
        $rutaDB = $ruta . $nombreArchivo;

        $guardar = Storage::put($ruta . $nombreArchivo, (string) file_get_contents($archivo), 'public');

        if ($guardar) {
            return ['estado' =>  'success', 'archivo' => $rutaDB];
        } else {
            return ['estado' =>  'failed', 'mensaje' => 'error al guardar el archivo.'];
        }
    }

    protected function ingresarCajaChica($request)
    {
        $validarDatos = $this->validarDatos($request);

        if ($validarDatos['estado'] == 'success') {

            $caja = new CajaChica;

            $fecha = $this->div_fecha($request->fecha);

            $anio = $this->anio_tipo_id($fecha['anio']);
            $mes = $this->mes_tipo_id($fecha['mes']);

            $existe = $this->existeCajaChica($anio->id, $mes->id);

            if ($existe['estado'] == 'success') {

                $total = $this->saldoActualCaja($anio->id, $mes->id);

                if (array_has($total, 'estado')) {
                    $sent = $request->monto <= $this->saldo;
                } else {
                    foreach ($total as $key) { }
                    $sent = $request->monto <= $key->saldo_actual;
                }

                if ($sent) {

                    $caja->anio_id = $anio->id;
                    $caja->mes_id = $fecha['mes'];
                    $caja->dia = $fecha['dia'];
                    $caja->numero_documento = $request->numero_documento;

                    $guardarArchivo = $this->guardarArchivo($request->archivo_documento, 'ArchivosCajaChica/');

                    if ($guardarArchivo['estado'] == "success") {
                        $caja->archivo_documento = 'storage/' . $guardarArchivo['archivo'];
                    } else {
                        return $guardarArchivo;
                    }

                    $caja->descripcion = $request->descripcion;

                    switch ($request->definicion) {
                        case '1':
                            $caja->monto_ingreso = $request->monto;
                            break;
                        case '2':
                            $caja->monto_egreso = $request->monto;
                            break;
                    }

                    $caja->definicion = $request->definicion;
                    $caja->user_crea = Auth::user()->id;
                    $caja->activo = "S";

                    if ($caja->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Insertado'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'No Insertado'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El monto ingresado excede al valor en Caja Chica'];
                }
            } else {
                return $existe;
            }
        } else {
            return $validarDatos;
        }
    }

    protected function verificarAModificar($id)
    {
        $verificar = DB::table('cs_caja_chica')
            ->where([
                'id' => $id,
                'activo' => 'S'
            ])
            ->first();
        if ($verificar) {
            return ['estado' => 'success', 'mensaje' => 'Todo Bien'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No Existe'];
        }
    }

    protected function modificarDatos($request)
    {

        $verificar = $this->verificarAModificar($request->id);

        if ($verificar['estado'] == 'success') {

            $modificar = CajaChica::find($request->id);

            switch ($request->campo) {
                case 'fecha':
                    $fecha = $this->div_fecha($request->input);

                    $anio = $this->anio_tipo_id($fecha['anio']);
                    $mes = $this->mes_tipo_id($fecha['mes']);

                    if ($modificar->anio_id == $anio->id && $modificar->mes_id == $mes->id) {
                        $modificar->dia = $fecha['dia'];

                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Fecha actualizada'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'Error al actualizar'];
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'La fecha ingresada no pertenece al mes correspondiente'];
                    }
                    break;
                case 'numero_documento':
                    $modificar->numero_documento = $request->input;

                    if ($modificar->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Numero de documento actualizado'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Error al actualizar'];
                    }
                    break;
                case 'archivo_documento':
                    $ruta = substr($modificar->archivo_documento, 8);
                    $borrar = Storage::delete($ruta);

                    if ($borrar) {
                        $guardarArchivo = $this->guardarArchivo($request->input, 'ArchivosCajaChica/');

                        if ($guardarArchivo['estado'] == "success") {
                            $modificar->archivo_documento = 'storage/' . $guardarArchivo['archivo'];
                            if ($modificar->save()) {
                                return ['estado' => 'success', 'mensaje' => 'Archivo Modificado'];
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'Error al actualizar'];
                            }
                        } else {
                            return $guardarArchivo;
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'No se pudo actualizar el archivo'];
                    }
                    break;
                case 'descripcion':
                    $modificar->descripcion = $request->input;

                    if ($modificar->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Descripcion actualizada'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Error al actualizar'];
                    }
                    break;
                case 'monto':
                    $modificar->monto_egreso = $request->input;

                    if ($modificar->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Monto actualizado'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Error al actualizar'];
                    }
                    break;
            }
        } else {
            return $verificar;
        }
    }

    protected function existeCajaChica($anio, $mes)
    {
        $cSindicato = DB::table('cuenta_sindicato')
            ->select([
                'tipo_cuenta_sindicato',
                'monto_egreso',
                'anio_id',
                'mes_id'
            ])
            ->where([
                'cuenta_sindicato.activo' => 'S',
                'cuenta_sindicato.anio_id' => $anio,
                'cuenta_sindicato.mes_id' => $mes,
                'cuenta_sindicato.tipo_cuenta_sindicato' => 3
            ])
            ->get();

        if (!$cSindicato->isEmpty()) {
            return ['estado' => 'success', 'mensaje' => 'La Caja Chica de este mes existe'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no ha realizado el ingreso a Caja Chica este mes.'];
        }
    }

    protected function traerCajaChica($anio, $mes)
    {
        $caja = DB::table('cs_caja_chica as cc')
            ->select([
                'cc.id',
                DB::raw("concat(cc.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'cc.numero_documento',
                'cc.archivo_documento',
                'cc.descripcion',
                'cc.monto_ingreso',
                'cc.monto_egreso',
                'cc.definicion'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->where([
                'cc.activo' => 'S',
                'cc.anio_id' => $anio,
                'cc.mes_id' => $mes,
            ])
            ->get();

        return $caja;
    }

    protected function totalIngEgre($anio, $mes)
    {
        $existe = $this->existeCajaChica($anio, $mes);

        if ($existe['estado'] == 'success') {
            $caja = DB::table('cs_caja_chica')
                ->select(DB::raw('sum(monto_ingreso) as total_ingreso, sum(monto_egreso) as total_egreso'))
                ->where([
                    'activo' => 'S',
                    'anio_id' => $anio,
                    'mes_id' => $mes,
                ])
                ->get();

            if (is_null($caja[0]->total_ingreso) && is_null($caja[0]->total_egreso)) {
                return ['estado' => 'failed', 'mensaje' => 'no hay ingresos en Caja Chica'];
            } else {
                if ($caja[0]->total_ingreso == 0) {
                    $caja[0]->total = $caja[0]->total_egreso;
                } else {
                    $caja[0]->total = $caja[0]->total_ingreso - $caja[0]->total_egreso;
                }
                return $caja;
            }
        } else {
            return $existe;
        }
    }
}
