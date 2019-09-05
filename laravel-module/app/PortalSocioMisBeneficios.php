<?php

namespace App;

use App\PortalSocio;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\CsPrestamo;

class PortalSocioMisBeneficios extends Model
{
    protected function socioID()
    {
        return PortalSocio::socioLogeado()->id;
    }

    protected function verificarSocio($id)
    {
        return PortalSocio::verificarSocio($id);
    }

    protected function traerPrestamos()
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $prestamos = DB::table('cs_prestamo as p')
                ->select([
                    'p.id',
                    DB::raw("concat(p.dia,' de ',m.descripcion,', ',a.descripcion) as fecha"),
                    'p.descripcion_prestamo as descripcion',
                    'p.transferencia_bancaria as codigo',
                    'p.archivo',
                    'p.egreso',
                    'p.cuotas',
                    'p.tipo_prestamo as tipo_id',
                    'tp.descripcion as tipo',
                    DB::raw("trim(p.estado_prestamo) as estado_prestamo"),
                    'p.estado_abono'
                ])
                ->join('anio as a', 'a.id', 'p.anio_id')
                ->join('mes as m', 'm.id', 'p.mes_id')
                ->join('tipo_prestamo as tp', 'tp.id', 'p.tipo_prestamo')
                ->orderBy('p.estado_prestamo', 'asc')
                ->orderBy('p.tipo_prestamo', 'asc')
                ->orderBy('p.dia', 'asc')
                ->where([
                    'p.activo' => 'S',
                    'p.socio_id' => $this->socioID()
                ])
                ->get();

            if (!$prestamos->isEmpty()) {

                foreach ($prestamos as $key) {
                    switch ($key->tipo_id) {
                        case 1:
                            if ($key->estado_abono != 'sin abonos') {
                                $key->tipo_sueldo = 1;
                                $key->tipo_conflicto = 2;
                                $key->tipo_trimestral = 3;
                            }
                            break;

                        default:
                            # code...
                            break;
                    }
                }

                $return = [];
                $return['vigente'] = [];
                $return['pagando'] = [];
                $return['finalizado'] = [];

                foreach ($prestamos as $key) {
                    switch ($key->estado_prestamo) {
                        case 'vigente':
                            $return['vigente'][] = $key;
                            break;

                        case 'pagando':
                            $return['pagando'][] = $key;
                            break;

                        case 'pagado':
                            $return['finalizado'][] = $key;
                            break;

                        case 'egresado':
                            $return['finalizado'][] = $key;
                            break;

                        default:
                            # code...
                            break;
                    }
                }
                //dd($return);
                return ['estado' => 'success', 'prestamos' => $return];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Aun no tienes prestamos pedidos.'];
            }
        } else {
            return $verificarSocio;
        }
    }

    protected function verificarPrestamoSocio($id, $tipo)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            if (!is_null($tipo)) {
                $prestamo = CsPrestamo::where([
                    'id' => $id,
                    'socio_id' => $this->socioID(),
                    'tipo_prestamo' => $tipo,
                    'activo' => 'S'
                ])
                    ->get();

                if (!$prestamo->isEmpty()) {
                    return ['estado' => 'success'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El prestamo al que intenas acceder no existe o no es tuyo.'];
                }
            } else {
                $prestamo = CsPrestamo::where([
                    'id' => $id,
                    'tipo_prestamo' => 1,
                    'socio_id' => $this->socioID(),
                    'activo' => 'S'
                ])
                    ->get();

                if (!$prestamo->isEmpty()) {
                    return ['estado' => 'success'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'El prestamo al que intenas acceder no existe o no es tuyo.'];
                }
            }
        } else {
            return $verificarSocio;
        }
    }

    protected function traerPagosPrestamos($id, $tipo)
    {
        $verificarPrestamo = $this->verificarPrestamoSocio($id, $tipo);
        if ($verificarPrestamo['estado'] == 'success') {
            switch ($tipo) {
                case 1:
                    $salud = DB::table('p_salud_retornable as psr')
                        ->select([
                            'psr.id',
                            'psr.prestamo_id',
                            DB::raw("concat(psr.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha_pago"),
                            /* 'psr.monto_dia_sueldo as monto_sueldo',
                            'psr.monto_trimestral',
                            'psr.monto_termino_conflicto as monto_conflicto', */
                            'psr.ingreso',
                            'psr.egreso',
                            'psr.numero_cuota',
                            DB::raw("concat(psr.numero_cuota,'/',psr.cuotas) as cuota"),
                            /* 'psr.monto_restante' */
                            /* 'psr.monto_restante',
                            DB::raw("concat(psr.numero_cuota,'/',psr.cuotas) as cuota"),
                            'psr.estado_prestamo',
                            'psr.estado_abono', */
                        ])
                        ->join('anio as a', 'a.id', 'psr.anio_id')
                        ->join('mes as m', 'm.id', 'psr.mes_id')
                        ->join('cs_prestamo as p', 'p.id', 'psr.prestamo_id')
                        ->where([
                            'psr.prestamo_id' => $id,
                            'p.activo' => 'S',
                            'p.socio_id' => $this->socioID()
                        ])
                        ->whereIn('psr.estado_prestamo', ['pagando', 'pagado'])
                        ->get();

                    if (!$salud->isEmpty()) {
                        $pagos['mensaje'] = [];
                        foreach ($salud as $key) {
                            $pagos['mensaje'][] = 'Se ha generado un pago por la cuota n°: ' . $key->cuota . ', el dia: ' . $key->fecha_pago . ', por un monto de: $' . number_format($key->ingreso, 0, '.', ',') . ' pesos.';
                            /* $key->tipo_sueldo = 1;
                            $key->tipo_conflicto = 2;
                            $key->tipo_trimestral = 3; */
                        }
                        return ['estado' => 'success', 'mensaje' => $pagos['mensaje']];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Aun no tienes pagos registrados en este prestamo.'];
                    }
                    break;

                case 2:
                    $apuro = DB::table('p_apuro_economico_retornable as pae')
                        ->select([
                            'pae.id',
                            'pae.prestamo_id',
                            DB::raw("concat(pae.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha_pago"),
                            'pae.interes_mensual',
                            'pae.ingreso',
                            'pae.egreso',
                            'pae.numero_cuota',
                            DB::raw("concat(pae.numero_cuota,'/',pae.cuotas) as cuota")
                            /* 'pae.monto_restante',
                            DB::raw("concat(pae.numero_cuota,'/',pae.cuotas) as cuota"),
                            'pae.estado_cuota' */
                        ])
                        ->join('anio as a', 'a.id', 'pae.anio_id')
                        ->join('mes as m', 'm.id', 'pae.mes_id')
                        ->join('cs_prestamo as p', 'p.id', 'pae.prestamo_id')
                        ->where([
                            'pae.prestamo_id' => $id,
                            'p.activo' => 'S',
                            'p.socio_id' => $this->socioID()
                        ])
                        ->whereIn('pae.estado_cuota', ['pagando', 'pagado'])
                        ->get();

                    if (!$apuro->isEmpty()) {
                        $pagos['mensaje'] = [];
                        foreach ($apuro as $key) {
                            /* <b></b> */
                            $pagos['mensaje'][] = 'Se ha generado un pago por la cuota n°: ' . $key->cuota . ', el dia: ' . $key->fecha_pago . ', por un monto de: $' . number_format($key->ingreso + $key->interes_mensual, 0, '.', ',') . ' pesos.';
                        }
                        return ['estado' => 'success', 'mensaje' => $pagos['mensaje']];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Aun no tienes pagos registrados en este prestamo.'];
                    }
                    break;

                case 3:
                    return ['estado' => 'success', 'mensaje' => 'El prestamo seleccionado no es retornable.'];
                    break;

                default:
                    # code...
                    break;
            }
        } else {
            return $verificarPrestamo;
        }
    }

    protected function traerPagosAbonos($id, $tipo)
    {
        $verificarPrestamo = $this->verificarPrestamoSocio($id, null);
        if ($verificarPrestamo['estado'] == 'success') {
            $abonos = DB::table('abonos_salud_retornable as asr')
                ->select([
                    'asr.id',
                    'asr.prestamo_id',
                    DB::raw("concat(asr.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha_pago"),
                    'asr.monto',
                    'asr.restante_abono'
                ])
                ->join('anio as a', 'a.id', 'asr.anio_id')
                ->join('mes as m', 'm.id', 'asr.mes_id')
                ->join('cs_prestamo as p', 'p.id', 'asr.prestamo_id')
                ->where([
                    'asr.prestamo_id' => $id,
                    'asr.abono_tipo' => $tipo,
                    'p.activo' => 'S',
                    'p.socio_id' => $this->socioID()
                ])
                ->get();

            if (!$abonos->isEmpty()) {
                $pagos['mensaje'] = [];
                foreach ($abonos as $key) {
                    $pagos['mensaje'][] = 'Se ha generado un pago el dia: ' . $key->fecha_pago . ', por un monto de: $' . number_format($key->monto, 0, '.', ',') . ' pesos, quedando asi un total de: $' . number_format($key->restante_abono, 0, '.', ',') . ' pesos por pagar en este tipo de abono.';
                }
                return ['estado' => 'success', 'mensaje' => $pagos['mensaje']];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Aun no tienes pagos registrados en este abono.'];
            }
        } else {
            return $verificarPrestamo;
        }
    }
}
