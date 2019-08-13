<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Cs_prestamo_tipo_abono_cuotas;
use App\DetallePrestamo;
use App\InteresPrestamo;
use App\MontoCierrePrestamo;
use Illuminate\Support\Facades\Storage;
use App\DetallePrestamoAbono;
use App\Detalleinteresprestamo;

class Cs_prestamos extends Model
{
    protected $table = 'cs_prestamos';

    protected $interes = 0.10;

    public function validarDatos($request, $opcion)
    {
        switch ($opcion) {
            case 1:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'fecha' => 'required',
                        'archivo_documento' => 'required|file|mimes:pdf',
                        'numero_documento' => 'required|unique:cs_prestamos,numero_documento',
                        'select_id' => 'required|min:0',
                        'monto_total' => 'required|integer|min:1',
                        'cuotas' => 'required|integer'

                    ],
                    [
                        'fecha.required' => 'La fecha es necesaria',
                        'archivo_documento.required' => 'Debe seleccionar un archivo',
                        'archivo_documento.file' => 'Lo seleccionado debe ser un archivo',
                        'archivo_documento.mimes' => 'El archivo debe ser extension PDF',
                        'numero_documento.required' => 'El numero de documento es necesario',
                        'numero_documento.unique' => 'El numero de documento ya existe en tus registros',
                        'monto_total.required' => 'El monto es necesario',
                        'monto_total.integer' => 'Debe ingresar solo numeros',
                        'monto_total.min' => 'El monto debe ser mayor a 0.',
                        'select_id.required' => 'Debe elegir el tipo de prestamo',
                        'cuotas.required' => 'Debe ingresar el numero de cuotas',
                        'cuotas.integer' => 'Debe ingresar solo numeros'
                    ]
                );
                break;
            case 2:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'detalle_prestamo_id' => 'required',
                        'fecha' => 'required',
                        'monto' => 'required|integer|min:1'
                    ],
                    [
                        'detalle_prestamo_id.required' => 'El ID del detalle prestamo es necesario',
                        'fecha.required' => 'La fecha es necesaria',
                        'monto.required' => 'El monto es necesario',
                        'monto.integer' => 'Debe ingresar solo numeros',
                        'monto.min' => 'El monto debe ser mayor a 0.',
                    ]
                );
                break;
            case 3:
                $validator = Validator::make(
                    $request->all(),
                    [
                        'detalle_prestamo_id' => 'required',
                        'fecha' => 'required',
                        'tipo_abono_id' => 'required',
                        'monto' => 'required|integer|min:1'
                    ],
                    [
                        'detalle_prestamo_id.required' => 'El ID del detalle prestamo es necesario',
                        'fecha.required' => 'La fecha es necesaria',
                        'tipo_abono_id.required' => 'Debe seleccionar el Tipo de abono a pagar.',
                        'monto.required' => 'El monto es necesario',
                        'monto.integer' => 'Debe ingresar solo numeros',
                        'monto.min' => 'El monto debe ser mayor a 0.',
                    ]
                );
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

    protected function ingresarPrestamo($request)
    {
        $validarDatos = $this->validarDatos($request, 1);

        if ($validarDatos['estado'] == 'success') {

            $fecha = $this->div_fecha($request->fecha);

            $anio = $this->anio_tipo_id($fecha['anio']);
            $mes = $this->mes_tipo_id($fecha['mes']);

            $verificarInicioM = $this->verificarInicioMensual($anio->id, $mes->id);

            if ($verificarInicioM['estado'] == 'success') {

                $prestamo = new Cs_prestamos;

                $select = DB::table('tipo_prestamo')
                    ->select([
                        'id',
                        'descripcion'
                    ])
                    ->where([
                        'id' => $request->select_id,
                        'activo' => 'S'
                    ])
                    ->first();

                $traerSocio = DB::table('socios')
                    ->where([
                        'id' => $request->socio_id,
                        'activo' => 'S'
                    ])
                    ->first();

                $prestamo->socio_id = $request->socio_id;
                $prestamo->dia = $fecha['dia'];
                $prestamo->mes_id = $mes->id;
                $prestamo->anio_id = $anio->id;
                $prestamo->numero_documento = $request->numero_documento;
                $prestamo->tipo_cuenta_sindicato = 4;

                switch ($request->select_id) {
                    case 1:

                        $prestamo->descripcion = 'Prestamo ' . $select->descripcion . ' pedido por ' . $traerSocio->nombres . ' ' . $traerSocio->a_paterno . ' ' . $traerSocio->a_materno;
                        $prestamo->monto_egreso = $request->monto_total;
                        $prestamo->definicion = 2;
                        $prestamo->tipo_prestamo_id = $request->select_id;

                        if ($request->checkAbono == 'true') {

                            $guardarArchivo = $this->guardarArchivo($request->archivo_documento, 'ArchivosPrestamo/');

                            if ($guardarArchivo['estado'] == "success") {
                                $prestamo->archivo_documento = 'storage/' . $guardarArchivo['archivo'];
                            } else {
                                return $guardarArchivo;
                            }

                            $prestamo->tipo_pago_id = 2;
                            $prestamo->user_crea = Auth::user()->id;
                            $prestamo->cuota = $request->cuotas;
                            $prestamo->activo = 'S';
                            $prestamo->estado_prestamo_id = 1;

                            if ($prestamo->save()) {
                                //$prestamo->id
                                $ultimoPrestamo = Cs_prestamos::find($prestamo->id);/* all()->last(); */

                                $dia = array(['id' => 1, 'check' => $request->checkdia, 'monto' => $request->monto_dia]);
                                $trimestral = array(['id' => 3, 'check' => $request->checktri, 'monto' => $request->monto_tri]);
                                $conflicto = array(['id' => 2, 'check' => $request->checkcon, 'monto' => $request->monto_con]);

                                $array = array_collapse([$dia, $trimestral, $conflicto]);

                                $totalesAbono = 0;

                                for ($i = 0; $i < count($array); $i++) {

                                    $prestamoAbono = new Cs_prestamo_tipo_abono_cuotas;
                                    $totalesAbono = $totalesAbono + $array[$i]['monto'];

                                    switch ($array[$i]['id']) {
                                        case 1:

                                            if ($array[$i]['check'] == 'true') {
                                                $prestamoAbono->cs_prestamo_id = $ultimoPrestamo->id;
                                                $prestamoAbono->tipo_abono_cuotas_id = 1;
                                                $prestamoAbono->monto = $array[$i]['monto'];
                                                $prestamoAbono->activo = 'S';

                                                if ($prestamoAbono->save()) {

                                                    $ultimoAbono = Cs_prestamo_tipo_abono_cuotas::all()->last();

                                                    $detalleAbono = new DetallePrestamoAbono;

                                                    $detalleAbono->prestamo_abono_id = $ultimoAbono->id;
                                                    $detalleAbono->anio_id = $anio->id;
                                                    $detalleAbono->mes_id = $mes->id;
                                                    $detalleAbono->dia = $fecha['dia'];
                                                    $detalleAbono->monto_egreso = $ultimoAbono->monto;
                                                    $detalleAbono->definicion = 2;
                                                    $detalleAbono->estado = 1;
                                                    $detalleAbono->activo = 'S';

                                                    if ($detalleAbono->save()) {
                                                        $array[$i]['paso'] = true;
                                                        break;
                                                    } else {
                                                        break;
                                                    }
                                                } else {
                                                    $array[$i]['paso'] = false;
                                                    break;
                                                }
                                            } else {
                                                break;
                                            }

                                            break;

                                        case 2:

                                            if ($array[$i]['check'] == 'true') {
                                                $prestamoAbono->cs_prestamo_id = $ultimoPrestamo->id;
                                                $prestamoAbono->tipo_abono_cuotas_id = 2;
                                                $prestamoAbono->monto = $array[$i]['monto'];
                                                $prestamoAbono->activo = 'S';

                                                if ($prestamoAbono->save()) {
                                                    $ultimoAbono = Cs_prestamo_tipo_abono_cuotas::all()->last();

                                                    $detalleAbono = new DetallePrestamoAbono;

                                                    $detalleAbono->prestamo_abono_id = $ultimoAbono->id;
                                                    $detalleAbono->anio_id = $anio->id;
                                                    $detalleAbono->mes_id = $mes->id;
                                                    $detalleAbono->dia = $fecha['dia'];
                                                    $detalleAbono->monto_egreso = $ultimoAbono->monto;
                                                    $detalleAbono->definicion = 2;
                                                    $detalleAbono->estado = 1;
                                                    $detalleAbono->activo = 'S';

                                                    if ($detalleAbono->save()) {
                                                        $array[$i]['paso'] = true;
                                                        break;
                                                    } else {
                                                        break;
                                                    }
                                                } else {
                                                    $array[$i]['paso'] = false;
                                                    break;
                                                }
                                            } else {
                                                break;
                                            }

                                            break;

                                        case 3:

                                            if ($array[$i]['check'] == 'true') {
                                                $prestamoAbono->cs_prestamo_id = $ultimoPrestamo->id;
                                                $prestamoAbono->tipo_abono_cuotas_id = 3;
                                                $prestamoAbono->monto = $array[$i]['monto'];
                                                $prestamoAbono->activo = 'S';

                                                if ($prestamoAbono->save()) {
                                                    $ultimoAbono = Cs_prestamo_tipo_abono_cuotas::all()->last();

                                                    $detalleAbono = new DetallePrestamoAbono;

                                                    $detalleAbono->prestamo_abono_id = $ultimoAbono->id;
                                                    $detalleAbono->anio_id = $anio->id;
                                                    $detalleAbono->mes_id = $mes->id;
                                                    $detalleAbono->dia = $fecha['dia'];
                                                    $detalleAbono->monto_egreso = $ultimoAbono->monto;
                                                    $detalleAbono->definicion = 2;
                                                    $detalleAbono->estado = 1;
                                                    $detalleAbono->activo = 'S';

                                                    if ($detalleAbono->save()) {
                                                        $array[$i]['paso'] = true;
                                                        break;
                                                    } else {
                                                        break;
                                                    }
                                                } else {
                                                    $array[$i]['paso'] = false;
                                                    break;
                                                }
                                            } else {
                                                break;
                                            }

                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }

                                $monto = $ultimoPrestamo->monto_egreso - $totalesAbono;

                                $detalle = new DetallePrestamo;

                                $detalle->prestamo_id = $ultimoPrestamo->id;
                                $detalle->anio_id = $ultimoPrestamo->anio_id;
                                $detalle->mes_id = $ultimoPrestamo->mes_id;
                                $detalle->dia = $ultimoPrestamo->dia;
                                $detalle->monto_egreso = $monto;
                                $detalle->activo = 'S';
                                $detalle->user_crea = Auth::user()->id;
                                $detalle->definicion = 2;
                                $detalle->cuota = 0;
                                $detalle->estado = 1;

                                if ($detalle->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Insertado salud abono'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'No Insertado salud abono'];
                                }
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'No Insertado salud abono'];
                            }
                        } else {
                            $prestamo->tipo_pago_id = 1;
                            $prestamo->user_crea = Auth::user()->id;
                            $prestamo->cuota = $request->cuotas;
                            $prestamo->activo = 'S';
                            $prestamo->estado_prestamo_id = 1;
                            $guardarArchivo = $this->guardarArchivo($request->archivo_documento, 'ArchivosPrestamo/');

                            if ($guardarArchivo['estado'] == "success") {
                                $prestamo->archivo_documento = 'storage/' . $guardarArchivo['archivo'];
                            } else {
                                return $guardarArchivo;
                            }

                            if ($prestamo->save()) {

                                $ultimoPrestamo = Cs_prestamos::find($prestamo->id);
                                $detalle = new DetallePrestamo;

                                $detalle->prestamo_id = $ultimoPrestamo->id;
                                $detalle->anio_id = $ultimoPrestamo->anio_id;
                                $detalle->mes_id = $ultimoPrestamo->mes_id;
                                $detalle->dia = $ultimoPrestamo->dia;
                                $detalle->monto_egreso = $ultimoPrestamo->monto_egreso;
                                $detalle->activo = 'S';
                                $detalle->user_crea = Auth::user()->id;
                                $detalle->definicion = 2;
                                $detalle->cuota = 0;
                                $detalle->estado = 1;

                                if ($detalle->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Insertado salud cuotas'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'No Insertado salud cuotas'];
                                }
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'No Insertado salud cuotas'];
                            }
                        }

                        break;

                    case 2:

                        $guardarArchivo = $this->guardarArchivo($request->archivo_documento, 'ArchivosPrestamo/');

                        if ($guardarArchivo['estado'] == "success") {
                            $prestamo->archivo_documento = 'storage/' . $guardarArchivo['archivo'];
                        } else {
                            return $guardarArchivo;
                        }
                        $prestamo->descripcion = 'Prestamo ' . $select->descripcion . ' pedido por ' . $traerSocio->nombres . ' ' . $traerSocio->a_paterno . ' ' . $traerSocio->a_materno;
                        $prestamo->monto_egreso = $request->monto_total;
                        $prestamo->definicion = 2;
                        $prestamo->tipo_prestamo_id = $request->select_id;
                        $prestamo->tipo_pago_id = 1;
                        $prestamo->user_crea = Auth::user()->id;
                        $prestamo->cuota = $request->cuotas;
                        $prestamo->activo = 'S';
                        $prestamo->estado_prestamo_id = 1;
                        $prestamo->interes_prestamo = $request->monto_total * $this->interes;

                        if ($prestamo->save()) {
                            $ultimoPrestamo = Cs_prestamos::find($prestamo->id);

                            $prestamoInteres = new InteresPrestamo;

                            $prestamoInteres->prestamo_id = $ultimoPrestamo->id;
                            $prestamoInteres->interes = $ultimoPrestamo->interes_prestamo;
                            $prestamoInteres->activo = 'S';

                            if ($prestamoInteres->save()) {

                                $detalle = new DetallePrestamo;

                                $monto_total = $ultimoPrestamo->monto_egreso * $this->interes;

                                $detalle->prestamo_id = $ultimoPrestamo->id;
                                $detalle->anio_id = $ultimoPrestamo->anio_id;
                                $detalle->mes_id = $ultimoPrestamo->mes_id;
                                $detalle->dia = $ultimoPrestamo->dia;
                                $detalle->monto_egreso = $ultimoPrestamo->monto_egreso /* + $monto_total */;
                                $detalle->activo = 'S';
                                $detalle->user_crea = Auth::user()->id;
                                $detalle->definicion = 2;
                                $detalle->cuota = 0;
                                $detalle->estado = 1;

                                if ($detalle->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Insertado apuro cuotas'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'No Insertado apuro cuotas'];
                                }
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'No Insertado apuro cuotas'];
                            }
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'No Insertado apuro cuotas'];
                        }
                        break;

                    case 3:
                        $guardarArchivo = $this->guardarArchivo($request->archivo_documento, 'ArchivosPrestamo/');

                        if ($guardarArchivo['estado'] == "success") {
                            $prestamo->archivo_documento = 'storage/' . $guardarArchivo['archivo'];
                        } else {
                            return $guardarArchivo;
                        }
                        $prestamo->descripcion = 'Prestamo ' . $select->descripcion . ' pedido por ' . $traerSocio->nombres . ' ' . $traerSocio->a_paterno . ' ' . $traerSocio->a_materno;
                        $prestamo->monto_egreso = $request->monto_total;
                        $prestamo->definicion = 2;
                        $prestamo->tipo_prestamo_id = $request->select_id;
                        $prestamo->tipo_pago_id = 3;
                        $prestamo->user_crea = Auth::user()->id;
                        $prestamo->cuota = 0;
                        $prestamo->activo = 'S';
                        $prestamo->estado_prestamo_id = 2;

                        if ($prestamo->save()) {
                            $ultimoPrestamo = Cs_prestamos::find($prestamo->id);

                            $detalle = new DetallePrestamo;

                            $detalle->prestamo_id = $ultimoPrestamo->id;
                            $detalle->anio_id = $ultimoPrestamo->anio_id;
                            $detalle->mes_id = $ultimoPrestamo->mes_id;
                            $detalle->dia = $ultimoPrestamo->dia;
                            $detalle->monto_egreso = $ultimoPrestamo->monto_egreso;
                            $detalle->activo = 'S';
                            $detalle->user_crea = Auth::user()->id;
                            $detalle->definicion = 2;
                            $detalle->cuota = 0;
                            $detalle->estado = 1;

                            if ($detalle->save()) {
                                return ['estado' => 'success', 'mensaje' => 'Insertado aporte'];
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'No Insertado aporte'];
                            }
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'No Insertado aporte'];
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            } else {
                return $verificarInicioM;
            }
        } else {
            return $validarDatos;
        }
    }

    protected function traerTipoPrestamos()
    {
        $tipo = DB::table('tipo_prestamo')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();

        return $tipo;
    }

    protected function traerTipoAbonos()
    {
        $tipo = DB::table('tipo_abono_cuotas')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();

        return $tipo;
    }

    protected function prestamosTotales($anio, $mes)
    {
        $prestamos = $this->traerPrestamos($anio, $mes);

        if ($prestamos['estado'] == 'success') {
            for ($i = 0; $i < count($prestamos['prestamos']); $i++) {

                $detallePrestamo = DetallePrestamo::find($prestamos['prestamos'][$i]->id);

                $fechaPrestamo = Cs_prestamos::select([
                    'cs_prestamos.anio_id',
                    'cs_prestamos.mes_id',
                    DB::raw("concat(cs_prestamos.dia,' de ',m.descripcion,',',a.descripcion) as fecha_prestamo"),
                ])
                    ->join('anio as a', 'a.id', 'cs_prestamos.anio_id')
                    ->join('mes as m', 'm.id', 'cs_prestamos.mes_id')
                    ->where([
                        'cs_prestamos.activo' => 'S',
                        'cs_prestamos.id' => $prestamos['prestamos'][$i]->prestamo_id
                    ])
                    ->get();

                $prestamos['prestamos'][$i]->fecha_prestamo = $fechaPrestamo[0]->fecha_prestamo;
                $abonos = $this->traerAbonos($anio, $mes, $prestamos['prestamos'][$i]->prestamo_id);
                /* dd($abonos); */
                $totalesAbono = 0;
                if ($abonos['estado'] == 'success') {
                    for ($e = 0; $e < count($abonos['abonos']); $e++) {
                        switch ($abonos['abonos'][$e]->tipo) {
                            case 1:
                                if (is_null($abonos['abonos'][$e]->monto_ingreso)) {
                                    $prestamos['prestamos'][$i]->monto_ingreso = $prestamos['prestamos'][$i]->monto_ingreso;
                                } else {
                                    $prestamos['prestamos'][$i]->monto_ingreso = $prestamos['prestamos'][$i]->monto_ingreso + $abonos['abonos'][$e]->monto_pagado;
                                }
                                if (is_null($abonos['abonos'][$e]->monto_egreso)) {
                                    $prestamos['prestamos'][$i]->sueldo = $abonos['abonos'][$e]->monto_egreso;
                                } else {
                                    $prestamos['prestamos'][$i]->sueldo = $abonos['abonos'][$e]->monto_egreso;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'conflicto')) {
                                    $prestamos['prestamos'][$i]->conflicto = 0;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'trimestral')) {
                                    $prestamos['prestamos'][$i]->trimestral = 0;
                                }
                                break;

                            case 2:
                                if (is_null($abonos['abonos'][$e]->monto_ingreso)) {
                                    $prestamos['prestamos'][$i]->monto_ingreso = $prestamos['prestamos'][$i]->monto_ingreso;
                                } else {
                                    $prestamos['prestamos'][$i]->monto_ingreso = $prestamos['prestamos'][$i]->monto_ingreso + $abonos['abonos'][$e]->monto_pagado;
                                }
                                if (is_null($abonos['abonos'][$e]->monto_egreso)) {
                                    $prestamos['prestamos'][$i]->conflicto = $abonos['abonos'][$e]->monto_egreso;
                                } else {
                                    $prestamos['prestamos'][$i]->conflicto = $abonos['abonos'][$e]->monto_egreso;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'sueldo')) {
                                    $prestamos['prestamos'][$i]->sueldo = 0;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'trimestral')) {
                                    $prestamos['prestamos'][$i]->trimestral = 0;
                                }
                                break;

                            case 3:
                                if (is_null($abonos['abonos'][$e]->monto_ingreso)) {
                                    $prestamos['prestamos'][$i]->monto_ingreso = $prestamos['prestamos'][$i]->monto_ingreso;
                                } else {
                                    $prestamos['prestamos'][$i]->monto_ingreso = $prestamos['prestamos'][$i]->monto_ingreso + $abonos['abonos'][$e]->monto_pagado;
                                }
                                if (is_null($abonos['abonos'][$e]->monto_egreso)) {
                                    $prestamos['prestamos'][$i]->trimestral = $abonos['abonos'][$e]->monto_egreso;
                                } else {
                                    $prestamos['prestamos'][$i]->trimestral = $abonos['abonos'][$e]->monto_egreso;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'sueldo')) {
                                    $prestamos['prestamos'][$i]->sueldo = 0;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'conflicto')) {
                                    $prestamos['prestamos'][$i]->conflicto = 0;
                                }
                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                    /* dd($abonos); */
                } else {
                    $prestamos['prestamos'][$i]->sueldo = 0;
                    $prestamos['prestamos'][$i]->conflicto = 0;
                    $prestamos['prestamos'][$i]->trimestral = 0;
                }

                if (is_null($prestamos['prestamos'][$i]->monto_ingreso)) {
                    $prestamos['prestamos'][$i]->monto_ingreso = 0;
                }
                $prestamos['prestamos'][$i]->monto_restante_p = $prestamos['prestamos'][$i]->total_prestamo_no_interes - ($prestamos['prestamos'][$i]->sueldo + $prestamos['prestamos'][$i]->conflicto + $prestamos['prestamos'][$i]->trimestral) - $prestamos['prestamos'][$i]->monto_ingreso;
                $prestamos['prestamos'][$i]->restante = $prestamos['prestamos'][$i]->total_prestamo_no_interes - $prestamos['prestamos'][$i]->monto_ingreso;
            }

            $return = [];
            $return['salud'] = [];
            $return['apuro'] = [];
            $return['aporte'] = [];

            foreach ($prestamos['prestamos'] as $key) {

                switch ($key->tipo_prestamo_id) {
                    case 1:
                        $return['salud'][] = $key;
                        break;

                    case 2:
                        $return['apuro'][] = $key;
                        break;

                    case 3:
                        $return['aporte'][] = $key;
                        break;

                    default:
                        # code...
                        break;
                }
            }
            return $return;
        } else {
            return $prestamos;
        }
    }

    protected function prestamosFinales($anio, $mes)
    {
        if ($mes == 1) {
            $anioA = $anio - 1;
            $mesA = 12;
        } else {
            $anioA = $anio;
            $mesA = $mes - 1;
        }

        $prestamosA = $this->traerPrestamos($anioA, $mesA);
        if ((array_has($prestamosA, 'estado'))) {
            if ($prestamosA['estado'] == 'success') {
                foreach ($prestamosA['prestamos'] as $key) {
                    $this->pasarPrestamoAbonoProxMes($key->id);
                    switch ($key->tipo_prestamo_id) {
                        case 1:
                            $this->pasarPrestamoAbonoProxMes($key->id);
                            break;

                        default:
                            # code...
                            break;
                    }
                }
            }

            return $this->prestamosTotales($anio, $mes);
        } else {
            return $this->prestamosTotales($anio, $mes);
        }
    }

    protected function traerPrestamos($anio, $mes)
    {
        $prestamo = DB::table('detalle_prestamo as pd')
            ->select([
                'pd.id',
                'pd.prestamo_id',
                'pd.anio_id',
                'pd.mes_id',
                DB::raw("concat(pd.dia,' de ',m.descripcion,',',a.descripcion) as fecha_pago"),
                'p.numero_documento',
                'p.archivo_documento',
                'p.descripcion',
                'p.monto_egreso as total_prestamo_no_interes',
                DB::raw("coalesce(ip.interes, 0) as interes"),
                DB::raw("(p.monto_egreso+coalesce(ip.interes, 0)) as total_prestamo"),
                DB::raw("concat(pd.cuota,'/',p.cuota) as cuota"),
                'pd.cuota as cuotad',
                'p.cuota as cuotap',
                'pd.monto_ingreso',
                'pd.monto_egreso',
                'ep.descripcion as estado',
                'pd.definicion',
                'p.tipo_prestamo_id'
            ])
            ->join('anio as a', 'a.id', 'pd.anio_id')
            ->join('mes as m', 'm.id', 'pd.mes_id')
            ->join('cs_prestamos as p', 'p.id', 'pd.prestamo_id')
            ->join('estado_prestamo as ep', 'ep.id', 'pd.estado')
            ->leftJoin('interes_prestamo as ip', 'ip.prestamo_id', 'pd.prestamo_id')
            ->where([
                'pd.activo' => 'S',
                'pd.anio_id' => $anio,
                'pd.mes_id' => $mes,
            ])
            ->orderby('pd.dia', 'ASC')
            ->get();

        if (!$prestamo->isEmpty()) {
            return ['estado' => 'success', 'prestamos' => $prestamo];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen prestamos en la fecha ingresada.'];
        }
    }

    protected function traerPrestamosHistorico($anio, $mes)
    {
        $prestamo = DB::table('cs_prestamos as p')
            ->select([
                'p.id',
                DB::raw("concat(p.dia,' de ',m.descripcion,',',a.descripcion) as fecha_prestamo"),
                'p.anio_id',
                'p.mes_id',
                'p.numero_documento',
                'p.archivo_documento',
                'p.descripcion',
                'p.monto_egreso as total_prestamo_no_interes',
                DB::raw("coalesce(ip.interes, 0) as interes"),
                'p.cuota as cuotap',
                'pd.monto_ingreso',
                'pd.monto_egreso',
                'ep.descripcion as estado',
                'p.tipo_prestamo_id'
            ])
            ->join('anio as a', 'a.id', 'p.anio_id')
            ->join('mes as m', 'm.id', 'p.mes_id')
            ->join('estado_prestamo as ep', 'ep.id', 'p.estado_prestamo_id')
            ->leftJoin('interes_prestamo as ip', 'ip.prestamo_id', 'p.id')
            ->join('detalle_prestamo as pd', 'pd.prestamo_id', 'p.id')
            ->where([
                'p.activo' => 'S',
                /* 'pd.activo' => 'S', */
                'p.anio_id' => $anio,
                'p.mes_id' => $mes,
                'pd.anio_id' => $anio,
                'pd.mes_id' => $mes
            ])
            ->orderby('p.dia', 'ASC')
            ->get();

        if (!$prestamo->isEmpty()) {
            for ($i = 0; $i < count($prestamo); $i++) {
                $abono = DB::table('cs_prestamo_tipo_abono_cuotas as pta')
                    ->select([
                        'pta.cs_prestamo_id',
                        'pta.tipo_abono_cuotas_id as tipo',
                        'pta.monto'
                    ])
                    ->where([
                        'pta.cs_prestamo_id' => $prestamo[$i]->id,
                        'pta.activo' => 'S'
                    ])
                    ->get();
                if (!$abono->isEmpty()) {
                    for ($e = 0; $e < count($abono); $e++) {
                        switch ($abono[$e]->tipo) {
                            case 1:
                                if (is_null($abono[$e]->monto)) {
                                    $prestamo[$i]->sueldo = $abono[$e]->monto;
                                } else {
                                    $prestamo[$i]->sueldo = $abono[$e]->monto;
                                }
                                if (!array_has($prestamo[$i], 'conflicto')) {
                                    $prestamo[$i]->conflicto = 0;
                                }
                                if (!array_has($prestamo[$i], 'trimestral')) {
                                    $prestamo[$i]->trimestral = 0;
                                }
                                break;
                            case 2:
                                if (is_null($abono[$e]->monto)) {
                                    $prestamo[$i]->conflicto = $abono[$e]->monto;
                                } else {
                                    $prestamo[$i]->conflicto = $abono[$e]->monto;
                                }
                                if (!array_has($prestamo[$i], 'sueldo')) {
                                    $prestamo[$i]->sueldo = 0;
                                }
                                if (!array_has($prestamo[$i], 'trimestral')) {
                                    $prestamo[$i]->trimestral = 0;
                                }
                                break;
                            case 3:
                                if (is_null($abono[$e]->monto)) {
                                    $prestamo[$i]->trimestral = $abono[$e]->monto;
                                } else {
                                    $prestamo[$i]->trimestral = $abono[$e]->monto;
                                }
                                if (!array_has($prestamo[$i], 'sueldo')) {
                                    $prestamo[$i]->sueldo = 0;
                                }
                                if (!array_has($prestamo[$i], 'conflicto')) {
                                    $prestamo[$i]->conflicto = 0;
                                }
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                } else {
                    $prestamo[$i]->sueldo = 0;
                    $prestamo[$i]->conflicto = 0;
                    $prestamo[$i]->trimestral = 0;
                }

                if (is_null($prestamo[$i]->monto_ingreso)) {
                    $prestamo[$i]->monto_ingreso = 0;
                }
                $prestamo[$i]->monto_restante_p = $prestamo[$i]->total_prestamo_no_interes - ($prestamo[$i]->sueldo + $prestamo[$i]->conflicto + $prestamo[$i]->trimestral) - $prestamo[$i]->monto_ingreso;
            }

            $return = [];
            $return['salud'] = [];
            $return['apuro'] = [];
            $return['aporte'] = [];

            foreach ($prestamo as $key) {

                switch ($key->tipo_prestamo_id) {
                    case 1:
                        $return['salud'][] = $key;
                        break;

                    case 2:
                        $return['apuro'][] = $key;
                        break;

                    case 3:
                        $return['aporte'][] = $key;
                        break;

                    default:
                        # code...
                        break;
                }
            }
            return $return;
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen prestamos en la fecha ingresada.'];
        }
    }

    protected function traerAbonos($anioP, $mesP, $prestamo_id)
    {
        $abonos = DB::table('detalle_prestamo_tipo_abono as dpta')
            ->select([
                'dpta.id',
                'pta.cs_prestamo_id',
                'dpta.prestamo_abono_id',
                'dpta.anio_id',
                'dpta.mes_id',
                'dpta.monto_ingreso',
                'dpta.monto_egreso',
                DB::raw("coalesce(dpta.monto_pagado, 0) as monto_pagado"),
                'pta.tipo_abono_cuotas_id as tipo',
                'dpta.estado'
            ])
            ->join('cs_prestamo_tipo_abono_cuotas as pta', 'pta.id', 'dpta.prestamo_abono_id')
            ->where([
                'dpta.anio_id' => $anioP,
                'dpta.mes_id' => $mesP,
                'pta.cs_prestamo_id' => $prestamo_id,
                'dpta.activo' => 'S'
            ])
            ->get();
        if (!$abonos->isEmpty()) {
            return ['estado' => 'success', 'abonos' => $abonos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen abonos.'];
        }
    }

    protected function pasarPrestamoAbonoProxMes($detalle_prestamo_id)
    {
        $detallePM = DetallePrestamo::find($detalle_prestamo_id);
        $prestamo = Cs_prestamos::find($detallePM->prestamo_id);

        if ($prestamo->tipo_prestamo_id == 1 && $detallePM->cuota == $prestamo->cuota && ($detallePM->estado == 1 || $detallePM->estado == 4)) {

            if ($detallePM->mes_id == 12) {
                $anio = $detallePM->anio_id + 1;
                $mes = 1;
            } else {
                $anio = $detallePM->anio_id;
                $mes = $detallePM->mes_id + 1;
            }

            $verificar = DetallePrestamo::where([
                'activo' => 'S',
                'prestamo_id' => $prestamo->id,
                'anio_id' => $anio,
                'mes_id' => $mes
            ])
                ->get();

            if ($verificar->isEmpty()) {
                $proxMes = new DetallePrestamo;
                $proxMes->prestamo_id = $detallePM->prestamo_id;
                $proxMes->anio_id = $anio;
                $proxMes->mes_id = $mes;
                $proxMes->dia = $detallePM->dia;
                $proxMes->monto_egreso = $detallePM->monto_egreso;
                $proxMes->activo = $detallePM->activo;
                $proxMes->user_crea = $detallePM->user_crea;
                $proxMes->monto_ingreso = $detallePM->monto_ingreso;
                $proxMes->definicion = 3;
                $proxMes->cuota = $detallePM->cuota;
                $proxMes->estado = 4;

                if ($proxMes->save()) {
                    $abonos = $this->traerAbonos($detallePM->anio_id, $detallePM->mes_id, $detallePM->prestamo_id);

                    if ($abonos['estado'] == 'success') {
                        $pasarPM = [];


                        foreach ($abonos['abonos'] as $key) {
                            switch ($key->tipo) {
                                case 1:
                                    $pasarPM[] = $key;
                                    break;
                                case 2:
                                    $pasarPM[] = $key;
                                    break;
                                case 3:
                                    $pasarPM[] = $key;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        }

                        for ($i = 0; $i < count($pasarPM); $i++) {
                            switch ($pasarPM[$i]->tipo) {
                                case 1:
                                    $detalleAbono = new DetallePrestamoAbono;

                                    $detalleAbono->prestamo_abono_id = $pasarPM[$i]->prestamo_abono_id;
                                    $detalleAbono->anio_id = $anio;
                                    $detalleAbono->mes_id = $mes;
                                    $detalleAbono->dia = $detallePM->dia;
                                    $detalleAbono->monto_ingreso = $pasarPM[$i]->monto_ingreso;
                                    $detalleAbono->monto_egreso = $pasarPM[$i]->monto_egreso;
                                    $detalleAbono->definicion = 3;
                                    $detalleAbono->estado = $pasarPM[$i]->estado;
                                    $detalleAbono->activo = 'S';
                                    if ($pasarPM[$i]->monto_pagado == 0) {
                                        $detalleAbono->monto_pagado = null;
                                    } else {
                                        $detalleAbono->monto_pagado = $pasarPM[$i]->monto_pagado;
                                    }

                                    if ($detalleAbono->save()) {
                                        break;
                                    } else {
                                        break;
                                    }
                                    break;
                                case 2:
                                    $detalleAbono = new DetallePrestamoAbono;

                                    $detalleAbono->prestamo_abono_id = $pasarPM[$i]->prestamo_abono_id;
                                    $detalleAbono->anio_id = $anio;
                                    $detalleAbono->mes_id = $mes;
                                    $detalleAbono->dia = $detallePM->dia;
                                    $detalleAbono->monto_ingreso = $pasarPM[$i]->monto_ingreso;
                                    $detalleAbono->monto_egreso = $pasarPM[$i]->monto_egreso;
                                    $detalleAbono->definicion = 3;
                                    $detalleAbono->estado = $pasarPM[$i]->estado;
                                    $detalleAbono->activo = 'S';
                                    if ($pasarPM[$i]->monto_pagado == 0) {
                                        $detalleAbono->monto_pagado = null;
                                    } else {
                                        $detalleAbono->monto_pagado = $pasarPM[$i]->monto_pagado;
                                    }

                                    if ($detalleAbono->save()) {
                                        break;
                                    } else {
                                        break;
                                    }
                                    break;
                                case 3:
                                    $detalleAbono = new DetallePrestamoAbono;

                                    $detalleAbono->prestamo_abono_id = $pasarPM[$i]->prestamo_abono_id;
                                    $detalleAbono->anio_id = $anio;
                                    $detalleAbono->mes_id = $mes;
                                    $detalleAbono->dia = $detallePM->dia;
                                    $detalleAbono->monto_ingreso = $pasarPM[$i]->monto_ingreso;
                                    $detalleAbono->monto_egreso = $pasarPM[$i]->monto_egreso;
                                    $detalleAbono->definicion = 3;
                                    $detalleAbono->estado = $pasarPM[$i]->estado;
                                    $detalleAbono->activo = 'S';
                                    if ($pasarPM[$i]->monto_pagado == 0) {
                                        $detalleAbono->monto_pagado = null;
                                    } else {
                                        $detalleAbono->monto_pagado = $pasarPM[$i]->monto_pagado;
                                    }

                                    if ($detalleAbono->save()) {
                                        break;
                                    } else {
                                        break;
                                    }
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        }
                        return ['estado' => 'success'];
                    } else {
                        return ['estado' => 'failed'];
                    }
                } else {
                    return ['estado' => 'failed'];
                }
            } else {
                return ['estado' => 'failed'];
            }
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function verificarInicioMensual($anio, $mes)
    {
        $verificar = DB::table('c_s_cierre_mensual')
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes,
            ])
            ->get();

        if (!$verificar->isEmpty()) {
            return ['estado' => 'success', 'mensaje' => 'Todo OK'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existe un Inicio mensual en este mes'];
        }
    }

    protected function traerSocio($rut)
    {
        $socio = DB::table('socios')
            ->select([
                'id',
                'rut',
                DB::raw("concat(nombres,' ',a_paterno,' ',a_materno) as socio"),
                'foto',
                'fecha_egreso'
            ])
            ->where([
                'rut' => $rut,
                'activo' => 'S',
            ])
            ->get();

        if (!$socio->isEmpty()) {
            if (is_null($socio[0]->fecha_egreso)) {
                return $socio;
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El socio ya no se encuentra activo en el sindicato.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no pertenece a ningun socio.'];
        }
    }
    protected function pagoPrestamos($request)
    {
        $validarDatos = $this->validarDatos($request, 2);

        if ($validarDatos['estado'] == 'success') {
            $fecha = $this->div_fecha($request->fecha);

            $anio = $this->anio_tipo_id($fecha['anio']);
            $mes = $this->mes_tipo_id($fecha['mes']);

            $verificarInicioM = $this->verificarInicioMensual($anio->id, $mes->id);

            if ($verificarInicioM['estado'] == 'success') {
                $dPrestamo = DetallePrestamo::find($request->detalle_prestamo_id);
                $prestamo = Cs_prestamos::find($dPrestamo->prestamo_id);

                /* if($request->monto > $dPrestamo->monto_egreso){
                    //
                }else{
                    //
                } */

                $abonos = $this->traerAbonos($dPrestamo->anio_id, $dPrestamo->mes_id, $dPrestamo->prestamo_id);

                $vigente = [];
                if ($abonos['estado'] == 'success') {
                    for ($i = 0; $i < count($abonos['abonos']); $i++) {
                        switch ($abonos['abonos'][$i]->tipo) {
                            case 1:
                                if ($abonos['abonos'][$i]->estado == 1) {
                                    $vigente['salud'] = true;
                                } else {
                                    $vigente['salud'] = false;
                                    break;
                                }
                                break;
                            case 2:
                                if ($abonos['abonos'][$i]->estado == 1) {
                                    $vigente['apuro'] = true;
                                } else {
                                    $vigente['apuro'] = false;
                                    break;
                                }
                                break;
                            case 3:
                                if ($abonos['abonos'][$i]->estado == 1) {
                                    $vigente['aporte'] = true;
                                } else {
                                    $vigente['aporte'] = false;
                                    break;
                                }
                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                    if (!array_has($vigente, 'salud')) {
                        $vigente['salud'] = false;
                    }
                    if (!array_has($vigente, 'apuro')) {
                        $vigente['apuro'] = false;
                    }
                    if (!array_has($vigente, 'aporte')) {
                        $vigente['aporte'] = false;
                    }
                    if ($vigente['salud'] == false && $vigente['apuro'] == false && $vigente['aporte'] == false) {
                        $estado = true;
                    } else {
                        $estado = false;
                    }
                } else {
                    $estado = true;
                }

                if ($dPrestamo->cuota < $prestamo->cuota && $prestamo->estado_prestamo_id == 1 && $dPrestamo->estado == 1) {
                    $pago = new DetallePrestamo;

                    $pago->prestamo_id = $dPrestamo->prestamo_id;
                    $pago->anio_id = $anio->id;
                    $pago->mes_id = $mes->id;
                    $pago->dia = $fecha['dia'];

                    if (!is_null($prestamo->interes_prestamo)) {
                        $dInteres = new Detalleinteresprestamo;

                        $idInteres = InteresPrestamo::where('prestamo_id', $dPrestamo->prestamo_id)->get();

                        $restar = $prestamo->interes_prestamo / $prestamo->cuota;
                        $dInteres->interes_prestamo_id = $idInteres[0]->id;
                        $dInteres->interes_mensual = $restar;
                        $dInteres->activo = "S";
                        $dInteres->anio_id = $anio->id;
                        $dInteres->mes_id = $mes->id;
                        $dInteres->dia = $fecha['dia'];
                        $dInteres->save();
                    } else {
                        $restar = 0;
                    }
                    $total = $request->monto - $restar;

                    $pago->monto_egreso = $dPrestamo->monto_egreso - $total;
                    $pago->activo = "S";
                    $pago->user_crea = Auth::user()->id;

                    if (is_null($dPrestamo->monto_ingreso)) {
                        $pago->monto_ingreso = $request->monto - $restar;
                    } else {
                        $pago->monto_ingreso = $dPrestamo->monto_ingreso + $request->monto - $restar;
                    }
                    $pago->definicion = 1;
                    $pago->cuota = $dPrestamo->cuota + 1;
                    $pago->estado = 1;

                    if ($pago->save()) {

                        if ($estado == true) {
                            $ultimoPago = DetallePrestamo::all()->last();

                            if ($ultimoPago->cuota == $prestamo->cuota) {
                                $ultimoPago->estado = 2;

                                if ($ultimoPago->save()) {
                                    $prestamo->estado_prestamo_id = 2;
                                    if ($prestamo->save()) {
                                        return ['estado' => 'success', 'mensaje' => 'Prestamo Finalizado'];
                                    } else {
                                        return ['estado' => 'success', 'mensaje' => 'Error'];
                                    }
                                } else {
                                    return ['estado' => 'success', 'mensaje' => 'Error'];
                                }
                            } else {
                                return ['estado' => 'success', 'mensaje' => 'Pago Realizado'];
                            }
                        } else {
                            /* dd($abonos); */
                            if ($abonos['estado'] == 'success') {
                                $pasarPM = [];


                                foreach ($abonos['abonos'] as $key) {
                                    switch ($key->tipo) {
                                        case 1:
                                            $pasarPM[] = $key;
                                            break;
                                        case 2:
                                            $pasarPM[] = $key;
                                            break;
                                        case 3:
                                            $pasarPM[] = $key;
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                }
                                //dd($pasarPM);
                                for ($e = 0; $e < count($pasarPM); $e++) {
                                  /*   dd($pasarPM[$i]->monto_ingreso); */
                                    //dump($pasarPM[$e]->tipo);
                                    switch ($pasarPM[$e]->tipo) {
                                        case 1:
                                            $detalleAbono = new DetallePrestamoAbono;

                                            $detalleAbono->prestamo_abono_id = $pasarPM[$e]->prestamo_abono_id;
                                            $detalleAbono->anio_id = $anio->id;
                                            $detalleAbono->mes_id = $mes->id;
                                            $detalleAbono->dia = $fecha['dia'];
                                            /* dd($pasarPM[$i]->monto_ingreso); */
                                            $detalleAbono->monto_ingreso = $pasarPM[$e]->monto_ingreso;
                                            $detalleAbono->monto_egreso = $pasarPM[$e]->monto_egreso;
                                            $detalleAbono->definicion = 3;
                                            $detalleAbono->estado = $pasarPM[$e]->estado;
                                            $detalleAbono->activo = 'S';
                                            if ($pasarPM[$e]->monto_pagado == 0) {
                                                $detalleAbono->monto_pagado = null;
                                            } else {
                                                $detalleAbono->monto_pagado = $pasarPM[$e]->monto_pagado;
                                            }

                                            if ($detalleAbono->save()) {
                                                break;
                                            } else {
                                                break;
                                            }
                                            break;
                                        case 2:
                                            $detalleAbono = new DetallePrestamoAbono;

                                            $detalleAbono->prestamo_abono_id = $pasarPM[$e]->prestamo_abono_id;
                                            $detalleAbono->anio_id = $anio->id;
                                            $detalleAbono->mes_id = $mes->id;
                                            $detalleAbono->dia = $fecha['dia'];
                                            $detalleAbono->monto_ingreso = $pasarPM[$e]->monto_ingreso;
                                            $detalleAbono->monto_egreso = $pasarPM[$e]->monto_egreso;
                                            $detalleAbono->definicion = 3;
                                            $detalleAbono->estado = $pasarPM[$e]->estado;
                                            $detalleAbono->activo = 'S';
                                            if ($pasarPM[$e]->monto_pagado == 0) {
                                                $detalleAbono->monto_pagado = null;
                                            } else {
                                                $detalleAbono->monto_pagado = $pasarPM[$e]->monto_pagado;
                                            }

                                            if ($detalleAbono->save()) {
                                                break;
                                            } else {
                                                break;
                                            }
                                            break;
                                        case 3:
                                            $detalleAbono = new DetallePrestamoAbono;

                                            $detalleAbono->prestamo_abono_id = $pasarPM[$e]->prestamo_abono_id;
                                            $detalleAbono->anio_id = $anio->id;
                                            $detalleAbono->mes_id = $mes->id;
                                            $detalleAbono->dia = $fecha['dia'];
                                            $detalleAbono->monto_ingreso = $pasarPM[$e]->monto_ingreso;
                                            $detalleAbono->monto_egreso = $pasarPM[$e]->monto_egreso;
                                            $detalleAbono->definicion = 3;
                                            $detalleAbono->estado = $pasarPM[$e]->estado;
                                            $detalleAbono->activo = 'S';
                                            if ($pasarPM[$e]->monto_pagado == 0) {
                                                $detalleAbono->monto_pagado = null;
                                            } else {
                                                $detalleAbono->monto_pagado = $pasarPM[$e]->monto_pagado;
                                            }

                                            if ($detalleAbono->save()) {
                                                break;
                                            } else {
                                                break;
                                            }
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                }
                                return ['estado' => 'success', 'mensaje' => 'Pago Realizado'];
                            } else {
                                return ['estado' => 'success', 'mensaje' => 'Pago Realizado'];
                            }
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error al realizar el pago'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El prestamo ya se encuentra pagado'];
                }
            } else {
                return $verificarInicioM;
            }
        } else {
            return $validarDatos;
        }
    }

    protected function pagoAbonos($request)
    {
        $validarDatos = $this->validarDatos($request, 3);

        if ($validarDatos['estado'] == 'success') {
            $fecha = $this->div_fecha($request->fecha);

            $anio = $this->anio_tipo_id($fecha['anio']);
            $mes = $this->mes_tipo_id($fecha['mes']);

            $verificarInicioM = $this->verificarInicioMensual($anio->id, $mes->id);

            if ($verificarInicioM['estado'] == 'success') {
                $dPrestamo = DetallePrestamo::find($request->detalle_prestamo_id);
                $prestamo = Cs_prestamos::find($dPrestamo->prestamo_id);

                $abono = DB::table('detalle_prestamo_tipo_abono as dpta')
                    ->join('cs_prestamo_tipo_abono_cuotas as pta', 'pta.id', 'dpta.prestamo_abono_id')
                    ->where([
                        'dpta.anio_id' => $dPrestamo->anio_id,
                        'dpta.mes_id' => $dPrestamo->mes_id,
                        'pta.tipo_abono_cuotas_id' => $request->tipo_abono_id,
                        'pta.cs_prestamo_id' => $prestamo->id,
                        'dpta.activo' => 'S'
                    ])
                    ->get();

                if (!$abono->isEmpty()) {
                    foreach ($abono as $key) { }
                    if ($request->monto <= $key->monto_egreso) {
                        if ($key->estado == 1) {
                            $dAbono = new DetallePrestamoAbono;
                            $dAbono->prestamo_abono_id = $key->prestamo_abono_id;
                            $dAbono->anio_id = $anio->id;
                            $dAbono->mes_id = $mes->id;
                            $dAbono->dia = $fecha['dia'];
                            if (is_null($key->monto_ingreso)) {
                                $dAbono->monto_ingreso = $request->monto;
                            } else {
                                $dAbono->monto_ingreso = $key->monto_ingreso + $request->monto;
                            }
                            $dAbono->monto_egreso = $key->monto_egreso - $request->monto;
                            $dAbono->definicion = 1;
                            $dAbono->estado = 1;
                            $dAbono->activo = "S";
                            $dAbono->monto_pagado = $request->monto;

                            if ($dAbono->save()) {
                                $ultimoPago = DetallePrestamoAbono::all()->last();
                                if ($ultimoPago->monto_egreso == 0) {
                                    $ultimoPago->estado = 2;
                                    if ($ultimoPago->save()) {

                                        $abonos = $this->traerAbonos($dPrestamo->anio_id, $dPrestamo->mes_id, $dPrestamo->prestamo_id);
                                        $vigente = [];

                                        if ($abonos['estado'] == 'success') {
                                            for ($i = 0; $i < count($abonos['abonos']); $i++) {
                                                switch ($abonos['abonos'][$i]->tipo) {
                                                    case 1:
                                                        if ($abonos['abonos'][$i]->estado == 1) {
                                                            $vigente['salud'] = true;
                                                        } else {
                                                            $vigente['salud'] = false;
                                                            break;
                                                        }
                                                        break;
                                                    case 2:
                                                        if ($abonos['abonos'][$i]->estado == 1) {
                                                            $vigente['apuro'] = true;
                                                        } else {
                                                            $vigente['apuro'] = false;
                                                            break;
                                                        }
                                                        break;
                                                    case 3:
                                                        if ($abonos['abonos'][$i]->estado == 1) {
                                                            $vigente['aporte'] = true;
                                                        } else {
                                                            $vigente['aporte'] = false;
                                                            break;
                                                        }
                                                        break;
                                                    default:
                                                        # code...
                                                        break;
                                                }
                                            }

                                            if (!array_has($vigente, 'salud')) {
                                                $vigente['salud'] = false;
                                            }
                                            if (!array_has($vigente, 'apuro')) {
                                                $vigente['apuro'] = false;
                                            }
                                            if (!array_has($vigente, 'aporte')) {
                                                $vigente['aporte'] = false;
                                            }

                                            if ($vigente['salud'] == false && $vigente['apuro'] == false && $vigente['aporte'] == false) {
                                                $estado = true;
                                            } else {
                                                $estado = false;
                                            }
                                            if ($estado == true) {
                                                if ($dPrestamo->cuota == $prestamo->cuota) {
                                                    $dPrestamo->estado = 2;
                                                    if ($dPrestamo->save()) {
                                                        $prestamo->estado_prestamo_id = 2;
                                                        if ($prestamo->save()) {
                                                            return ['estado' => 'success', 'mensaje' => 'Prestamo Finalizado'];
                                                        } else {
                                                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error al realizar el pago'];
                                                        }
                                                    } else {
                                                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error al realizar el pago'];
                                                    }
                                                } else {
                                                    return ['estado' => 'success', 'mensaje' => 'Pago Realizado'];
                                                }
                                            } else {
                                                return ['estado' => 'success', 'mensaje' => 'Pago Realizado'];
                                            }
                                        } else {
                                            return $abonos;
                                        }
                                    } else {
                                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error al realizar el pago'];
                                    }
                                } else {
                                    return ['estado' => 'success', 'mensaje' => 'Pago Realizado'];
                                }
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error al realizar el pago'];
                            }
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'El Abono ya se encuentra pagado'];
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'El monto ingresado es mayor al abono a pagar'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El Prestamo a pagar no posee abonos'];
                }
            } else {
                return $verificarInicioM;
            }
        } else {
            return $validarDatos;
        }
    }
}
