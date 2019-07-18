<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Cs_prestamo_tipo_abono_cuotas;

class Cs_prestamos extends Model
{
    protected $table = 'cs_prestamos';

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
                //'checkAbono' => 'required',
                'cuotas' => 'required'

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
                'cuotas.required' => 'Debe ingresar el numero de cuotas'
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

    protected function ingresarPrestamo($request)
    {
        //return $request->all();

        $prestamo = new Cs_prestamos;

        $fecha = $this->div_fecha($request->fecha);

        $anio = $this->anio_tipo_id($fecha['anio']);
        $mes = $this->mes_tipo_id($fecha['mes']);

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


        //verificar campos

        $prestamo->socio_id = $request->socio_id;
        $prestamo->dia = $fecha['dia'];
        $prestamo->mes_id = $mes->id;
        $prestamo->anio_id = $anio->id;
        $prestamo->numero_documento = $request->numero_documento;
        $prestamo->tipo_cuenta_sindicato = 4;

        switch ($request->select_id) {
            case 1:
                //prestamo salud - retornable

                //dd($request->all());

                $prestamo->descripcion = 'Prestamo ' . $select->descripcion . ' pedido por ' . $traerSocio->nombres . ' ' . $traerSocio->a_paterno;
                $prestamo->monto_egreso = $request->monto_total;
                $prestamo->definicion = 2;
                $prestamo->tipo_prestamo_id = $request->select_id;
                $prestamo->archivo_documento = '/doc/archivo.pdf'; //valor por mientras

                if ($request->checkAbono == 'true') {

                    //return 'paso if';
                    $prestamo->tipo_pago_id = 2;
                    $prestamo->user_crea = Auth::user()->id;
                    $prestamo->cuota = $request->cuotas;
                    $prestamo->activo = 'S';

                    if ($prestamo->save()) {

                        $ultimoPrestamo = Cs_prestamos::all()->last();

                        //verificar todos los check de abono
                        $dia = array(['id' => 1, 'check' => $request->checkdia, 'monto' => $request->monto_dia]);
                        $trimestral = array(['id' => 3, 'check' => $request->checktri, 'monto' => $request->monto_tri]);
                        $conflicto = array(['id' => 2, 'check' => $request->checkcon, 'monto' => $request->monto_con]);

                        $array = array_collapse([$dia, $trimestral, $conflicto]);

                        //dd($array);

                        for ($i = 0; $i < count($array); $i++) {

                            $prestamoAbono = new Cs_prestamo_tipo_abono_cuotas;

                            switch ($array[$i]['id']) {
                                case 1:

                                    if ($array[$i]['check'] == 'true') {
                                        $prestamoAbono->cs_prestamo_id = $ultimoPrestamo->id;
                                        $prestamoAbono->tipo_abono_cuotas_id = 1;
                                        $prestamoAbono->monto = $array[$i]['monto'];
                                        $prestamoAbono->activo = 'S';

                                        if ($prestamoAbono->save()) {
                                            $array[$i]['paso'] = true;
                                            break;
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
                                            $array[$i]['paso'] = true;
                                            break;
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
                                            $array[$i]['paso'] = true;
                                            break;
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

                        return ['estado' => 'success', 'mensaje' => 'Insertado salud abono'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'No Insertado salud abono'];
                    }
                } else {
                    //return 'paso else';
                    $prestamo->tipo_pago_id = 1;
                    $prestamo->user_crea = Auth::user()->id;
                    $prestamo->cuota = $request->cuotas;
                    $prestamo->activo = 'S';

                    if ($prestamo->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Insertado salud cuotas'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'No Insertado salud cuotas'];
                    }
                }

                break;

            case 2:
                //prestamo apuro economico - retornable
                $prestamo->archivo_documento = '/doc/archivo.pdf'; //valor por mientras
                $prestamo->descripcion = 'Prestamo ' . $select->descripcion . ' pedido por ' . $traerSocio->nombres . ' ' . $traerSocio->a_paterno;
                $prestamo->monto_egreso = $request->monto_total;
                $prestamo->definicion = 2;
                $prestamo->tipo_prestamo_id = $request->select_id;
                $prestamo->tipo_pago_id = 1;
                $prestamo->user_crea = Auth::user()->id;
                $prestamo->cuota = 4;
                $prestamo->activo = 'S';

                if ($prestamo->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Insertado apuro cuotas'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'No Insertado apuro cuotas'];
                }
                break;

            case 3:
                //prestamo aporte economico - no retornable
                //monto prestamo
                $prestamo->archivo_documento = '/doc/archivo.pdf'; //valor por mientras
                $prestamo->descripcion = 'Prestamo ' . $select->descripcion . ' pedido por ' . $traerSocio->nombres . ' ' . $traerSocio->a_paterno;
                $prestamo->monto_egreso = $request->monto_total;
                $prestamo->definicion = 2;
                $prestamo->tipo_prestamo_id = $request->select_id;
                $prestamo->tipo_pago_id = 3;
                $prestamo->user_crea = Auth::user()->id;
                $prestamo->cuota = 0;
                $prestamo->activo = 'S';

                if ($prestamo->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Insertado aporte'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'No Insertado aporte'];
                }
                break;

            default:
                # code...
                break;
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

    /* protected function traerTipoPago()
    {
        $tipo = DB::table('tipo_pago')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();

        return $tipo;
    } */

    /* protected function traerTipos()
    {
        $tPre = $this->traerTipoPrestamos();
        $tPa = $this->traerTipoPago();

        return ['tipo_prestamo' => $tPre, 'tipo_pago' => $tPa];
    } */

    /* protected function traerTipoAbono()
    {
        $tipoA = DB::table('tipo_abono_cuotas')
            ->select([
                'id',
                'tipo_pago_id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();
        return $tipoA;
    } */

    protected function traerSocio($rut)
    {
        $socio = DB::table('socios')
            ->select([
                'id',
                'rut',
                DB::raw("concat(nombres,' ',a_paterno,' ',a_materno) as socio"),
                'foto'
            ])
            ->where([
                'rut' => $rut,
                'activo' => 'S',
            ])
            ->get();

        if (!$socio->isEmpty()) {
            return $socio;
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no pertenece a ningun socio.'];
        }
    }
}
