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

class Cs_prestamos extends Model
{
    protected $table = 'cs_prestamos';

    protected $interes = 0.10;

    public function validarDatos($request)
    {
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
                'monto.min' => 'El monto debe ser mayor a 0.',
                'select_id.required' => 'Debe elegir el tipo de prestamo',
                'cuotas.required' => 'Debe ingresar el numero de cuotas',
                'cuotas.integer' => 'Debe ingresar solo numeros'
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
        $validarDatos = $this->validarDatos($request);

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

                                $ultimoPrestamo = Cs_prestamos::all()->last();

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
                                                $prestamoAbono->cs_prestamo_id = $ultimoPrestamo->cs_prestamo_id;
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

                                $ultimoPrestamo = Cs_prestamos::all()->last();
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
                        $prestamo->cuota = 4;
                        $prestamo->activo = 'S';
                        $prestamo->estado_prestamo_id = 1;
                        $prestamo->interes_prestamo = $request->monto_total * $this->interes;

                        if ($prestamo->save()) {
                            $ultimoPrestamo = Cs_prestamos::all()->last();

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
                                $detalle->monto_egreso = $ultimoPrestamo->monto_egreso + $monto_total;
                                $detalle->activo = 'S';
                                $detalle->user_crea = Auth::user()->id;
                                $detalle->definicion = 2;
                                $detalle->cuota = 0;

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
                        $prestamo->estado_prestamo_id = 1;

                        if ($prestamo->save()) {
                            $ultimoPrestamo = Cs_prestamos::all()->last();

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
        //$tomar = true;

        if ($prestamos['estado'] == 'success') {
            for ($i = 0; $i < count($prestamos['prestamos']); $i++) {
                /* dd($this->traerAbonos(102)); */
                $abonos = $this->traerAbonos($anio, $mes, $prestamos['prestamos'][$i]->prestamo_id);

                if ($abonos['estado'] == 'success') {
                    for ($e = 0; $e < count($abonos['abonos']); $e++) {
                        switch ($abonos['abonos'][$e]->tipo) {
                            case 1:
                                if (is_null($abonos['abonos'][$e]->monto_egreso)) {
                                    $prestamos['prestamos'][$i]->sueldo = $abonos['abonos'][$e]->monto_egreso - 0;
                                } else {
                                    $prestamos['prestamos'][$i]->sueldo = $abonos['abonos'][$e]->monto_egreso - $abonos['abonos'][$e]->monto_ingreso;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'conflicto')) {
                                    $prestamos['prestamos'][$i]->conflicto = 0;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'trimestral')) {
                                    $prestamos['prestamos'][$i]->trimestral = 0;
                                }
                                break;

                            case 2:
                                if (is_null($abonos['abonos'][$e]->monto_egreso)) {
                                    $prestamos['prestamos'][$i]->conflicto = $abonos['abonos'][$e]->monto_egreso - 0;
                                } else {
                                    $prestamos['prestamos'][$i]->conflicto = $abonos['abonos'][$e]->monto_egreso - $abonos['abonos'][$e]->monto_ingreso;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'sueldo')) {
                                    $prestamos['prestamos'][$i]->sueldo = 0;
                                }
                                if (!array_has($prestamos['prestamos'][$i], 'trimestral')) {
                                    $prestamos['prestamos'][$i]->trimestral = 0;
                                }
                                break;

                            case 3:
                                if (is_null($abonos['abonos'][$e]->monto_egreso)) {
                                    $prestamos['prestamos'][$i]->trimestral = $abonos['abonos'][$e]->monto_egreso - 0;
                                } else {
                                    $prestamos['prestamos'][$i]->trimestral = $abonos['abonos'][$e]->monto_egreso - $abonos['abonos'][$e]->monto_ingreso;
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
                } else {
                    $prestamos['prestamos'][$i]->sueldo = 0;
                    $prestamos['prestamos'][$i]->conflicto = 0;
                    $prestamos['prestamos'][$i]->trimestral = 0;
                }

               /*  if (is_null($prestamos['prestamos'][$i]->monto_ingreso)) {
                    $prestamos['prestamos'][$i]->monto_ingreso = 0;
                } */
                /* $prestamos['prestamos'][$i]->saldo_restante =  $prestamos['prestamos'][$i]->total_prestamo - $prestamos['prestamos'][$i]->monto_ingreso - $prestamos['prestamos'][$i]->trimestral - $prestamos['prestamos'][$i]->sueldo - $prestamos['prestamos'][$i]->conflicto; */
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

    protected function traerPrestamos($anio, $mes)
    {
        $prestamo = DB::table('detalle_prestamo as pd')
            ->select([
                'pd.id',
                'pd.prestamo_id',
                DB::raw("concat(pd.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'p.numero_documento',
                'p.archivo_documento',
                'p.descripcion',
                'p.monto_egreso as total_prestamo_no_interes',
                DB::raw("coalesce(ip.interes, 0) as interes"),
                DB::raw("(p.monto_egreso+coalesce(ip.interes, 0)) as total_prestamo"),
                DB::raw("concat(pd.cuota,'/',p.cuota) as cuota"),
                'pd.monto_ingreso',
                'pd.monto_egreso',
                'ep.descripcion as estado',
                'pd.definicion',
                'p.tipo_prestamo_id'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->join('cs_prestamos as p', 'p.id', 'pd.prestamo_id')
            ->join('estado_prestamo as ep', 'ep.id', 'p.estado_prestamo_id')
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

    protected function traerAbonos($anio, $mes, $prestamo_id)
    {
        $abonos = DB::table('detalle_prestamo_tipo_abono as dpta')
            ->select([
                'dpta.id',
                'pta.cs_prestamo_id',
                'dpta.monto_ingreso',
                'dpta.monto_egreso',
                'pta.tipo_abono_cuotas_id as tipo'
            ])
            ->join('cs_prestamo_tipo_abono_cuotas as pta', 'pta.id', 'dpta.prestamo_abono_id')
            ->where([
                'dpta.anio_id' => $anio,
                'dpta.mes_id' => $mes,
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
        $fecha = $this->div_fecha($request->fecha);

        $anio = $this->anio_tipo_id($fecha['anio']);
        $mes = $this->mes_tipo_id($fecha['mes']);

        $dPrestamo = DetallePrestamo::find($request->detalle_prestamo_id);
        $prestamo = Cs_prestamos::find($dPrestamo->prestamo_id);

        /* dd($dPrestamo->cuota <= $prestamo->cuota);
        dd($prestamo); */

        if ($dPrestamo->cuota < $prestamo->cuota && $prestamo->estado_prestamo_id == 1) {
            $pago = new DetallePrestamo;

            $pago->prestamo_id = $dPrestamo->prestamo_id;
            $pago->anio_id = $anio->id;
            $pago->mes_id = $mes->id;
            $pago->dia = $fecha['dia'];
            $pago->monto_egreso = $dPrestamo->monto_egreso - $request->monto;
            $pago->activo = "S";
            $pago->user_crea = Auth::user()->id;
            if(is_null($dPrestamo->monto_ingreso)){
                $pago->monto_ingreso = $request->monto;
            }else{
                $pago->monto_ingreso = $dPrestamo->monto_ingreso + $request->monto;
            }
            $pago->definicion = 1;
            $pago->cuota = $dPrestamo->cuota + 1;

            if ($pago->save()) {
                return ['estado' => 'success', 'mensaje' => 'Pago Realizado'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error al realizar el pago'];
            }
        }else{
            return ['estado' => 'failed', 'mensaje' => 'El prestamo ya se encuentra pagado'];
        }
    }
}
