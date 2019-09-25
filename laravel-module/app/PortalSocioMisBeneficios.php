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
                            $pagos['mensaje'][] = 'Se ha generado un pago por la cuota n°: <b>' . $key->cuota . '</b>, el dia: <b>' . $key->fecha_pago . '</b>, por un monto de: $<b>' . number_format($key->ingreso, 0, '.', ',') . '</b> pesos.';
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
                            $pagos['mensaje'][] = 'Se ha generado un pago por la cuota n°: <b>' . $key->cuota . '</b>, el dia: <b>' . $key->fecha_pago . '</b>, por un monto de: $<b>' . number_format($key->ingreso + $key->interes_mensual, 0, '.', ',') . '</b> pesos.';
                        }
                        return ['estado' => 'success', 'mensaje' => $pagos['mensaje']];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Aun no tienes pagos registrados en este prestamo.'];
                    }
                    break;

                case 3:
                    return ['estado' => 'failed', 'mensaje' => 'El prestamo seleccionado no es retornable.'];
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
                    $pagos['mensaje'][] = 'Se ha generado un pago el dia: <b>' . $key->fecha_pago . '</b>, por un monto de: $<b>' . number_format($key->monto, 0, '.', ',') . '</b> pesos, quedando asi un total de: $<b>' . number_format($key->restante_abono, 0, '.', ',') . '</b> pesos por pagar en este tipo de abono.';
                }
                return ['estado' => 'success', 'mensaje' => $pagos['mensaje']];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Aun no tienes pagos registrados en este abono.'];
            }
        } else {
            return $verificarPrestamo;
        }
    }

    protected function traerNacimientos()
    {
        $MN = DB::table('cuenta_bienestar as cb')
            ->select([
                'cb.id',
                DB::raw("concat(cb.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha_cobro"),
                'cb.numero_documento_1 as codigo',
                'cb.archivo_documento_1 as comprobante',
                'cb.monto_egreso as monto',
                'cn.rut_nacimiento as rut'
            ])
            ->join('anio as a', 'a.id', 'cb.anio_id')
            ->join('mes as m', 'm.id', 'cb.mes_id')
            ->join('cbe_nacimiento as cn', 'cn.cuenta_bienestar_id', 'cb.id')
            ->orderBy('cb.dia', 'asc')
            ->where([
                'cb.activo' => 'S',
                'cb.tipo_cuenta_bienestar_id' => 5,
                'cn.activo' => 'S',
                'cn.socio_id' => $this->socioID()
            ])
            ->get();

        if (!$MN->isEmpty()) {
            foreach ($MN as $key) {
                $carga =  DB::table('cargas_legales_socio')
                    ->select([
                        DB::raw("concat(nombres,' ',apellido_paterno,' ',apellido_materno) as nombre"),
                        'archivo as certificado'
                    ])
                    ->where([
                        'activo' => 'S',
                        'socio_id' => $this->socioID(),
                        'rut' => $key->rut
                    ])
                    ->get();

                if (!$carga->isEmpty()) {
                    $key->nombre = $carga[0]->nombre;
                    $key->certificado = $carga[0]->certificado;
                }
            }
            return ['estado' => 'success', 'nacimientos' => $MN];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes beneficios por nacimiento cobrados.'];
        }
    }

    protected function traerFallecimientos()
    {
        $MF = DB::table('cuenta_bienestar as cb')
            ->select([
                'cb.id',
                DB::raw("concat(cb.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha_cobro"),
                'cb.numero_documento_1 as codigo',
                'cb.archivo_documento_1 as comprobante',
                'cb.archivo_documento_2 as certificado',
                'cb.monto_egreso as monto',
                'cf.rut_fallecido as rut'
            ])
            ->join('anio as a', 'a.id', 'cb.anio_id')
            ->join('mes as m', 'm.id', 'cb.mes_id')
            ->join('cbe_fallecimiento as cf', 'cf.cuenta_bienestar_id', 'cb.id')
            ->orderBy('cb.dia', 'asc')
            ->where([
                'cb.activo' => 'S',
                'cb.tipo_cuenta_bienestar_id' => 4,
                'cf.activo' => 'S',
                'cf.socio_id' => $this->socioID()
            ])
            ->get();

        if (!$MF->isEmpty()) {
            foreach ($MF as $key) {
                $fallecido = $this->verificarFallecido($key->rut);
                $key->nombre = $fallecido['nombre'];
            }
            return ['estado' => 'success', 'fallecimientos' => $MF];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes beneficios por fallecimiento cobrados.'];
        }
    }

    protected function verificarFallecido($rut)
    {
        $existeConyuge = false;
        $existeBeneficiario = false;
        $existeCarga = false;
        $existePS = false;

        $conyuge = DB::table('socio_conyuge')
            ->select([
                DB::raw("concat(nombres,' ',apellido_paterno,' ',apellido_materno) as nombre"),
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioID(),
                'rut' => $rut
            ])
            ->get();

        if (!$conyuge->isEmpty()) {
            return ['estado' => 'success', 'nombre' => $conyuge[0]->nombre];
        } else {
            $existeConyuge = true;
        }

        $beneficiario = DB::table('socio_beneficiario')
            ->select([
                DB::raw("concat(nombres,' ',apellido_paterno,' ',apellido_materno) as nombre"),
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioID(),
                'rut' => $rut
            ])
            ->get();

        if (!$beneficiario->isEmpty()) {
            return ['estado' => 'success', 'nombre' => $beneficiario[0]->nombre];
        } else {
            $existeBeneficiario = true;
        }

        $carga = DB::table('cargas_legales_socio')
            ->select([
                DB::raw("concat(nombres,' ',apellido_paterno,' ',apellido_materno) as nombre"),
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioID(),
                'rut' => $rut
            ])
            ->get();

        if (!$carga->isEmpty()) {
            return ['estado' => 'success', 'nombre' => $carga[0]->nombre];
        } else {
            $existeCarga = true;
        }

        $PS = DB::table('padres_suegros_socio')
            ->select([
                DB::raw("concat(nombres,' ',apellido_paterno,' ',apellido_materno) as nombre"),
            ])
            ->where([
                'activo' => 'S',
                'socio_id' => $this->socioID(),
                'rut' => $rut
            ])
            ->get();

        if (!$PS->isEmpty()) {
            return ['estado' => 'success', 'nombre' => $PS[0]->nombre];
        } else {
            $existePS = true;
        }

        if ($existeConyuge && $existeBeneficiario && $existeCarga && $existePS) {
            return ['estado' => 'failed', 'mensaje' => 'No existe el fallecido o no esta relacionado con el socio.'];
        }
    }

    protected function traerGastosMedicos()
    {
        $MGM = DB::table('cuenta_bienestar as cb')
            ->select([
                'cb.id',
                DB::raw("concat(cb.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha_cobro"),
                'cb.numero_documento_1 as codigo',
                'cb.archivo_documento_1 as comprobante',
                'cb.monto_egreso as monto',
                'cb.descripcion'
            ])
            ->join('anio as a', 'a.id', 'cb.anio_id')
            ->join('mes as m', 'm.id', 'cb.mes_id')
            ->join('cbe_gastos_medicos as cmg', 'cmg.cuenta_bienestar_id', 'cb.id')
            ->orderBy('cb.dia', 'asc')
            ->where([
                'cb.activo' => 'S',
                'cb.tipo_cuenta_bienestar_id' => 7,
                'cmg.activo' => 'S',
                'cmg.socio_id' => $this->socioID()
            ])
            ->get();

        if (!$MGM->isEmpty()) {
            return ['estado' => 'success', 'GM' => $MGM];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes beneficios por gastos medicos cobrados.'];
        }
    }

    protected function traerMisAhorros($anio)
    {
        $ahorro = DB::table('cuenta_consorcio as cc')
            ->select([
                'id',
                DB::raw("coalesce(cc.monto_mes_ds_1, 0) as mes_ds_1"),
                DB::raw("coalesce(cc.monto_mes_cex_1, 0) as mes_cex_1"),
                DB::raw("coalesce(cc.monto_mes_ds_2, 0) as mes_ds_2"),
                DB::raw("coalesce(cc.monto_mes_cex_2, 0) as mes_cex_2"),
                DB::raw("coalesce(cc.monto_mes_ds_3, 0) as mes_ds_3"),
                DB::raw("coalesce(cc.monto_mes_cex_3, 0) as mes_cex_3"),
                DB::raw("coalesce(cc.monto_mes_ds_4, 0) as mes_ds_4"),
                DB::raw("coalesce(cc.monto_mes_cex_4, 0) as mes_cex_4"),
                DB::raw("coalesce(cc.monto_mes_ds_5, 0) as mes_ds_5"),
                DB::raw("coalesce(cc.monto_mes_cex_5, 0) as mes_cex_5"),
                DB::raw("coalesce(cc.monto_mes_ds_6, 0) as mes_ds_6"),
                DB::raw("coalesce(cc.monto_mes_cex_6, 0) as mes_cex_6"),
                DB::raw("coalesce(cc.monto_mes_ds_7, 0) as mes_ds_7"),
                DB::raw("coalesce(cc.monto_mes_cex_7, 0) as mes_cex_7"),
                DB::raw("coalesce(cc.monto_mes_ds_8, 0) as mes_ds_8"),
                DB::raw("coalesce(cc.monto_mes_cex_8, 0) as mes_cex_8"),
                DB::raw("coalesce(cc.monto_mes_ds_9, 0) as mes_ds_9"),
                DB::raw("coalesce(cc.monto_mes_cex_9, 0) as mes_cex_9"),
                DB::raw("coalesce(cc.monto_mes_ds_10, 0) as mes_ds_10"),
                DB::raw("coalesce(cc.monto_mes_cex_10, 0) as mes_cex_10"),
                DB::raw("coalesce(cc.monto_mes_ds_11, 0) as mes_ds_11"),
                DB::raw("coalesce(cc.monto_mes_cex_11, 0) as mes_cex_11"),
                DB::raw("coalesce(cc.monto_mes_ds_12, 0) as mes_ds_12"),
                DB::raw("coalesce(cc.monto_mes_cex_12, 0) as mes_cex_12")
            ])
            ->where([
                'cc.vinculado' => 'S',
                'cc.anio_id' => $anio,
                'cc.socio_id' => $this->socioID()
            ])
            ->get();

        if (!$ahorro->isEmpty()) {
            $sum = 0;
            foreach ($ahorro[0] as $key) {
                $sum = $sum + $key;
            }

            $total = $sum-$ahorro[0]->id;

            return ['estado' => 'success', 'ahorro' => $ahorro, 'total' => $total];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes ahorros en el año ingresado.'];
        }
    }
}
