<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CajaChicaBienestar extends Model
{
    protected $table = "cb_caja_chica";

    public function validarDatos($request, $opcion)
    {
        switch ($opcion) {
            case 1:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'fecha' => 'required',
                        'numero_documento' => 'required|unique:cb_caja_chica,numero_documento',
                        'archivo_documento' => 'required|file|mimes:pdf',
                        'descripcion' => 'required',
                        'monto' => 'required|integer|min:1',
                        'definicion' => 'required'

                    ],
                    [
                        'fecha.required' => 'Debes ingresar la fecha.',
                        'numero_documento.required' => 'Debes ingresar un n° de documento.',
                        'numero_documento.unique' => 'El numero de documento ya existe en tus registros.',
                        'archivo_documento.required' => 'Debes seleccionar un archivo.',
                        'archivo_documento.file' => 'Lo seleccionado debe ser un archivo.',
                        'archivo_documento.mimes' => 'El archivo debe ser extension PDF.',
                        'descripcion.required' => 'Debes ingresar una descripcion.',
                        'monto.required' => 'Debes ingresar un monto.',
                        'monto.min' => 'El monto ingresado debe ser mayor a 0.',
                        'monto.integer' => 'Debes ingresar solo numeros.',
                        'definicion.required' => 'Especifique si su detalle es ingreso o egreso.'
                    ]
                );
                break;

            case 2:
                switch ($request->campo) {
                    case 'fecha':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required'
                            ],
                            [
                                'input.required' => 'Debes ingresar la fecha.'
                            ]
                        );
                        break;
                    case 'numero_documento':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required|unique:cb_caja_chica,numero_documento'
                            ],
                            [
                                'input.required' => 'Debes ingresar un n° de documento.',
                                'input.unique' => 'El numero de documento ya existe en tus registros.'
                            ]
                        );
                        break;

                    case 'archivo_documento':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required|file|mimes:pdf'
                            ],
                            [
                                'input.required' => 'Debes seleccionar un archivo.',
                                'input.file' => 'Lo seleccionado debe ser un archivo.',
                                'input.mimes' => 'El archivo debe ser extension PDF.'
                            ]
                        );
                        break;

                    case 'descripcion':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required'
                            ],
                            [
                                'input.required' => 'Debes ingresar una descripcion.'
                            ]
                        );
                        break;

                    case 'monto':
                        $validator = Validator::make(
                            $request->all(),
                            [
                                'input' => 'required|integer|min:1'
                            ],
                            [
                                'input.required' => 'Debes ingresar un monto.',
                                'input.min' => 'El monto ingresado debe ser mayor a 0.',
                                'input.integer' => 'Debes ingresar solo numeros.'
                            ]
                        );
                        break;

                    default:
                        # code...
                        break;
                }
                break;

            default:
                # code...
                break;
        }
        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function fecha($value)
    {
        $fecha = $value;
        $anio = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $anio, 'mes' => $mes, 'dia' => $dia
        ];
    }
    protected function anioID($value)
    {
        return DB::table('anio')->where('descripcion', $value)->first()->id;
    }

    protected function mesID($value)
    {
        return DB::table('mes')->where('id', $value)->first()->id;
    }

    protected function existeInicioMensual($anio, $mes)
    {
        $existe = DB::table('cbe_cierre_mensual')
            ->select([
                'inicio_mensual'
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes
            ])
            ->get();

        if (!$existe->isEmpty()) {
            return ['estado' => 'success'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no se ha realizado el ingreso a inicio mensual en la fecha ingresada.'];
        }
    }

    protected function calcularValorCajaChicaMesAnterior($anio, $mes)
    {
        $anioA = $anio;
        $mesA = $mes - 1;

        if ($mesA == 0) {
            $mesA = 12;
            $anioA = $anio - 1;
        }

        $caja = DB::table('cuenta_bienestar')
            ->select([
                'monto_egreso'
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anioA,
                'mes_id' => $mesA,
                'tipo_cuenta_bienestar_id' => 6
            ])
            ->get();

        if (!$caja->isEmpty()) {
            $monto = DB::table('cb_caja_chica')
                ->select([
                    'monto_egreso'
                ])
                ->where([
                    'activo' => 'S',
                    'anio_id' => $anioA,
                    'mes_id' => $mesA
                ])
                ->sum('monto_egreso');
            $resta = $caja[0]->monto_egreso - $monto;
            return ['estado' => 'success', 'monto' => $resta];
        } else {
            return ['estado' => 'failed', 'monto' => 0];
        }
    }

    protected function existeCajaChica($anio, $mes)
    {
        $IM = $this->existeInicioMensual($anio, $mes);
        if ($IM['estado'] == 'success') {
            $existe = DB::table('cuenta_bienestar')
                ->select([
                    'monto_egreso'
                ])
                ->where([
                    'activo' => 'S',
                    'anio_id' => $anio,
                    'mes_id' => $mes,
                    'tipo_cuenta_bienestar_id' => 6
                ])
                ->get();

            if (!$existe->isEmpty()) {
                $montoAnterior = $this->calcularValorCajaChicaMesAnterior($anio, $mes);
                $monto = $montoAnterior['monto'] + $existe[0]->monto_egreso;
                return ['estado' => 'success', 'monto_caja' => $monto];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Aun no ha realizado el ingreso a Caja Chica este mes.'];
            }
        } else {
            return $IM;
        }
    }

    protected function saldoActualCajaChica($anio, $mes, $MCC)
    {
        $saldo = DB::table('cb_caja_chica')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as monto_ingreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as monto_egreso')
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes
            ])
            ->get();
        if (!$saldo->isEmpty()) {
            $MA = 0;
            if ($saldo[0]->monto_ingreso == 0) {
                $MA = $MCC - $saldo[0]->monto_egreso;
            } else {
                $resta = $saldo[0]->monto_ingreso - $saldo[0]->monto_egreso;
                $MA = $MCC - $resta;
            }
            return ['estado' => 'success', 'saldo' => $MA];
        } else {
            return ['estado' => 'failed', 'saldo' => $MCC];
        }
    }

    protected function guardarArchivo($archivo, $ruta)
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

    protected function ingresarCajaChica($request)
    {
        $fecha = $this->fecha($request->fecha);
        $anio = $this->anioID($fecha['anio']);
        $mes = $this->mesID($fecha['mes']);

        $existe = $this->existeCajaChica($anio, $mes);
        if ($existe['estado'] == 'success') {
            $saldo = $this->saldoActualCajaChica($anio, $mes, $existe['monto_caja']);
            if (array_has($saldo, 'estado')) {
                $validarDatos = $this->validarDatos($request, 1);
                if ($validarDatos['estado'] == 'success') {
                    if ($request->monto <= $saldo['saldo']) {
                        $caja = new CajaChicaBienestar;
                        $caja->anio_id = $anio;
                        $caja->mes_id = $mes;
                        $caja->dia = $fecha['dia'];
                        $caja->numero_documento = $request->numero_documento;
                        $guardarArchivo = $this->guardarArchivo($request->archivo_documento, 'ArchivosCajaChicaBienestar/');
                        if ($guardarArchivo['estado'] == "success") {
                            $caja->archivo_documento = $guardarArchivo['archivo'];
                            $caja->descripcion = $request->descripcion;
                            switch ($request->definicion) {
                                case '1':
                                    $caja->monto_ingreso = $request->monto;
                                    break;
                                case '2':
                                    $caja->monto_egreso = $request->monto;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            $caja->definicion = $request->definicion;
                            $caja->user_crea = Auth::user()->id;
                            $caja->activo = "S";
                            if ($caja->save()) {
                                return ['estado' => 'success', 'mensaje' => 'Datos ingresados correctamente.'];
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                            }
                        } else {
                            return $guardarArchivo;
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'El monto que intentas ingresar excede al saldo actual en Caja Chica.'];
                    }
                } else {
                    return $validarDatos;
                }
            }
        } else {
            return $existe;
        }
    }

    protected function modificarCajaChica($request)
    {
        $validarDatos = $this->validarDatos($request, 2);
        if ($validarDatos['estado'] == 'success') {
            $modificar = CajaChicaBienestar::find($request->id);

            if (!is_null($modificar)) {
                switch ($request->campo) {
                    case 'fecha':
                        $fecha = $this->fecha($request->input);
                        $anio = $this->anioID($fecha['anio']);
                        $mes = $this->mesID($fecha['mes']);

                        $modificar->anio_id = $anio;
                        $modificar->mes_id = $mes;
                        $modificar->dia = $fecha['dia'];
                        //dd($modificar);
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Fecha actualizada correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'numero_documento':
                        $modificar->numero_documento = $request->input;
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'N° de documento actualizado correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'archivo_documento':
                        $ruta = substr($modificar->archivo_documento, 8);
                        $borrar = Storage::delete($ruta);
                        if ($borrar) {
                            $guardarArchivo = $this->guardarArchivo($request->input, 'ArchivosCajaChicaBienestar/');
                            if ($guardarArchivo['estado'] == 'success') {
                                $modificar->archivo_documento = $guardarArchivo['archivo'];
                                if ($modificar->save()) {
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
                        break;

                    case 'descripcion':
                        $modificar->descripcion = $request->input;
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Descripcion actualizada correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'monto':
                        $modificar->monto_egreso = $request->input;
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Monto actualizado correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El Item que intentas modificar no existe.'];
            }
        } else {
            return $validarDatos;
        }
    }

    protected function traerCajaChicaTotal($anio, $mes)
    {
        $existe = $this->existeCajaChica($anio, $mes);
        if ($existe['estado'] == 'success') {
            $total = $this->totalesCajaChica($anio, $mes);
            if ($total['estado'] == 'success') {
                return $this->traerCajaChica($anio, $mes, $existe['monto_caja'], $total['totales']);
            } else {
                return $total;
            }
        } else {
            return $existe;
        }
    }

    protected function traerCajaChica($anio, $mes, $MC, $total)
    {
        $caja = DB::table('cb_caja_chica as cc')
            ->select([
                'cc.id',
                DB::raw("concat(cc.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha"),
                'cc.numero_documento',
                'cc.archivo_documento',
                DB::raw("upper(cc.descripcion) as descripcion"),
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
            ->orderby('cc.dia', 'ASC')
            ->get();

        if (!$caja->isEmpty()) {
            $tomar = true;
            for ($i = 0; $i < count($caja); $i++) {
                switch ($caja[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $caja[$i]->saldo_actual = $MC + $caja[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $caja[$i]->saldo_actual = $caja[$i - 1]->saldo_actual + $caja[$i]->monto_ingreso;
                        }
                        break;
                    case 2:
                        if ($tomar == true) {
                            $caja[$i]->saldo_actual = $MC - $caja[$i]->monto_egreso;
                            $tomar = false;
                        } else {
                            $caja[$i]->saldo_actual = $caja[$i - 1]->saldo_actual - $caja[$i]->monto_egreso;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }
            $total->cierre_mes = $total->total + $MC;
            return ['estado' => 'success', 'monto_inicio' => $MC, 'caja' => $caja, 'total' => $total];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function totalesCajaChica($anio, $mes)
    {
        $totales = DB::table('cb_caja_chica')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as total_ingreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as total_egreso'),
                DB::raw('sum(coalesce(monto_ingreso, 0)) - sum(coalesce(monto_egreso, 0)) as total')
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes,
            ])
            ->get();

        if (!$totales->isEmpty()) {
            return ['estado' => 'success', 'totales' => $totales[0]];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }
}
