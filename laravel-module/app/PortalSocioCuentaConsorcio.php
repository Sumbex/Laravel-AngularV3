<?php

namespace App;

use App\PortalSocio;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PortalSocioCuentaConsorcio extends Model
{
    protected function socioID()
    {
        return PortalSocio::socioLogeado()->id;
    }

    protected function verificarSocio($id)
    {
        return PortalSocio::verificarSocio($id);
    }

    protected function traerCuentaConsorcioAnual($anio)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $consorcio = DB::table('cuenta_consorcio')
                ->select([
                    DB::raw("sum(coalesce(monto_mes_ds_1, 0)) as mes_ds_1"),
                    DB::raw("sum(coalesce(monto_mes_cex_1, 0)) as mes_cex_1"),
                    DB::raw("sum(coalesce(monto_mes_ds_2, 0)) as mes_ds_2"),
                    DB::raw("sum(coalesce(monto_mes_cex_2, 0)) as mes_cex_2"),
                    DB::raw("sum(coalesce(monto_mes_ds_3, 0)) as mes_ds_3"),
                    DB::raw("sum(coalesce(monto_mes_cex_3, 0)) as mes_cex_3"),
                    DB::raw("sum(coalesce(monto_mes_ds_4, 0)) as mes_ds_4"),
                    DB::raw("sum(coalesce(monto_mes_cex_4, 0)) as mes_cex_4"),
                    DB::raw("sum(coalesce(monto_mes_ds_5, 0)) as mes_ds_5"),
                    DB::raw("sum(coalesce(monto_mes_cex_5, 0)) as mes_cex_5"),
                    DB::raw("sum(coalesce(monto_mes_ds_6, 0)) as mes_ds_6"),
                    DB::raw("sum(coalesce(monto_mes_cex_6, 0)) as mes_cex_6"),
                    DB::raw("sum(coalesce(monto_mes_ds_7, 0)) as mes_ds_7"),
                    DB::raw("sum(coalesce(monto_mes_cex_7, 0)) as mes_cex_7"),
                    DB::raw("sum(coalesce(monto_mes_ds_8, 0)) as mes_ds_8"),
                    DB::raw("sum(coalesce(monto_mes_cex_8, 0)) as mes_cex_8"),
                    DB::raw("sum(coalesce(monto_mes_ds_9, 0)) as mes_ds_9"),
                    DB::raw("sum(coalesce(monto_mes_cex_9, 0)) as mes_cex_9"),
                    DB::raw("sum(coalesce(monto_mes_ds_10, 0)) as mes_ds_10"),
                    DB::raw("sum(coalesce(monto_mes_cex_10, 0)) as mes_cex_10"),
                    DB::raw("sum(coalesce(monto_mes_ds_11, 0)) as mes_ds_11"),
                    DB::raw("sum(coalesce(monto_mes_cex_11, 0)) as mes_cex_11"),
                    DB::raw("sum(coalesce(monto_mes_ds_12, 0)) as mes_ds_12"),
                    DB::raw("sum(coalesce(monto_mes_cex_12, 0)) as mes_cex_12")
                ])
                ->where([
                    'anio_id' => $anio
                ])
                ->get();

            if (!$consorcio->isEmpty()) {
                $ttrue = false;

                foreach ($consorcio[0] as $key) {
                    if (!is_null($key)) {
                        $ttrue = true;
                    }
                }
                if ($ttrue) {
                    $DSCE = $this->restaDSCEMensual($anio);
                    return ['estado' => 'success', 'mensual' => $consorcio, 'anual' => $DSCE['anual'], 'DSCE' => $DSCE['DSCE']];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'No existen registros en el año ingresado.'];
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'No existen registros en el año ingresado.'];
            }
        } else {
            return $verificarSocio;
        }
    }

    protected function restaDSCEMensual($anio)
    {
        $DS = DB::table('cuenta_consorcio')
            ->select([
                DB::raw("sum(coalesce(monto_mes_ds_1, 0) - coalesce(monto_mes_cex_1, 0)) as mes_1"),
                DB::raw("sum(coalesce(monto_mes_ds_2, 0) - coalesce(monto_mes_cex_2, 0)) as mes_2"),
                DB::raw("sum(coalesce(monto_mes_ds_3, 0) - coalesce(monto_mes_cex_3, 0)) as mes_3"),
                DB::raw("sum(coalesce(monto_mes_ds_4, 0) - coalesce(monto_mes_cex_4, 0)) as mes_4"),
                DB::raw("sum(coalesce(monto_mes_ds_5, 0) - coalesce(monto_mes_cex_5, 0)) as mes_5"),
                DB::raw("sum(coalesce(monto_mes_ds_6, 0) - coalesce(monto_mes_cex_6, 0)) as mes_6"),
                DB::raw("sum(coalesce(monto_mes_ds_7, 0) - coalesce(monto_mes_cex_7, 0)) as mes_7"),
                DB::raw("sum(coalesce(monto_mes_ds_8, 0) - coalesce(monto_mes_cex_8, 0)) as mes_8"),
                DB::raw("sum(coalesce(monto_mes_ds_9, 0) - coalesce(monto_mes_cex_9, 0)) as mes_9"),
                DB::raw("sum(coalesce(monto_mes_ds_10, 0) - coalesce(monto_mes_cex_10, 0)) as mes_10"),
                DB::raw("sum(coalesce(monto_mes_ds_11, 0) - coalesce(monto_mes_cex_11, 0)) as mes_11"),
                DB::raw("sum(coalesce(monto_mes_ds_12, 0) - coalesce(monto_mes_cex_12, 0)) as mes_12")
            ])
            ->where([
                'anio_id' => $anio
            ])
            ->get();

        if (!$DS->isEmpty()) {
            $ttrue = false;
            $sum = 0;
            foreach ($DS[0] as $key) {
                $sum = $sum + $key;
                if (!is_null($key)) {
                    $ttrue = true;
                }
            }
            if ($ttrue) {
                return ['estado' => 'success', 'DSCE' => $DS, 'anual' => $sum];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'No existen registros en el año ingresado.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen registros en el año ingresado.'];
        }
    }

    //->pasar a consorcio
    protected function sociosDesvinculados($anio)
    {
        $socios = DB::table('cc_pago_beneficios as cpb')
            ->select([
                'cpb.socio_id',
                DB::raw("upper(concat(s.nombres,' ',s.a_paterno,' ',s.a_materno)) as nombre"),
                DB::raw("upper(cpb.descripcion) as descripcion"),
                DB::raw("upper(concat(m.descripcion,' del ',a.descripcion)) as fecha"),
                DB::raw("concat(cast(cpb.porcentaje as float) * 100, '%') as porcentaje"),
                'cpb.monto_dia_sueldo as total_DS',
                's.fecha_egreso'
            ])
            ->join('socios as s', 's.id', 'cpb.socio_id')
            ->join('mes as m', 'm.id', 'cpb.mes_id')
            ->join('anio as a', 'a.id', 'cpb.anio_id')
            ->where([
                'cpb.anio_id' => $anio,
                's.retiro_pago_beneficio' => 'S'
            ])
            ->get();

        if (!$socios->isEmpty()) {
            foreach ($socios as $key) {
                $des = $this->totalesSociosDesvinculados($anio, $key->socio_id);
                $key->total_ADS = $des['total'];
                $key->total = $key->total_DS + $key->total_ADS;
            }
            return ['estado' => 'success', 'socios' => $socios];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen registros de socios desvinculados en el año ingresado.'];
        }
    }

    protected function totalesSociosDesvinculados($anio, $socio_id)
    {
        $DSCE = DB::table('cuenta_consorcio as cc')
            ->select([
                DB::raw("(COALESCE(monto_mes_ds_1,0) +
           COALESCE(monto_mes_ds_2,0) +
           COALESCE(monto_mes_ds_3,0) +
           COALESCE(monto_mes_ds_4,0) +
           COALESCE(monto_mes_ds_5,0) +
           COALESCE(monto_mes_ds_6,0) +
           COALESCE(monto_mes_ds_7,0) +
           COALESCE(monto_mes_ds_8,0) +
           COALESCE(monto_mes_ds_9,0)+
           COALESCE(monto_mes_ds_10,0) +
           COALESCE(monto_mes_ds_11,0) +
           COALESCE(monto_mes_ds_12,0)) as total_ds_socio"),
                DB::raw("(COALESCE(monto_mes_cex_1,0) +
            COALESCE(monto_mes_cex_2,0) +
            COALESCE(monto_mes_cex_3,0) +
            COALESCE(monto_mes_cex_4,0) +
            COALESCE(monto_mes_cex_5,0) +
            COALESCE(monto_mes_cex_6,0) +
            COALESCE(monto_mes_cex_7,0) +
            COALESCE(monto_mes_cex_8,0) +
            COALESCE(monto_mes_cex_9,0)+ 
            COALESCE(monto_mes_cex_10,0) +
            COALESCE(monto_mes_cex_11,0) +
            COALESCE(monto_mes_cex_12,0)) as total_cex_socio")
            ])
            ->where([
                'cc.anio_id' => $anio,
                'cc.socio_id' => $socio_id,
                'cc.vinculado' => 'N'
            ])
            ->get();

        if (!$DSCE->isEmpty()) {
            $total = $DSCE[0]->total_ds_socio - $DSCE[0]->total_cex_socio;
            return ['estado' => 'success', 'total' => $total];
        } else {
            return ['estado' => 'failed'];
        }
    }
}
