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
                $tfalse = true;
                $sum = 0;
                foreach ($consorcio[0] as $key) {
                    $sum = $sum + $key;
                    if (is_null($key)) {
                        $tfalse = false;
                    } else {
                        $ttrue = true;
                    }
                }
                if ($ttrue) {
                    $DSCE = $this->sumaDSCEMensual($anio);
                    return ['estado' => 'success', 'mensual' => $consorcio, 'anual' => $sum, 'DSCE' => $DSCE['DSCE']];
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

    protected function sumaDSCEMensual($anio)
    {
        $DS = DB::table('cuenta_consorcio')
            ->select([
                DB::raw("sum(coalesce(monto_mes_ds_1, 0) + coalesce(monto_mes_cex_1, 0)) as mes_1"),
                DB::raw("sum(coalesce(monto_mes_ds_2, 0) + coalesce(monto_mes_cex_2, 0)) as mes_2"),
                DB::raw("sum(coalesce(monto_mes_ds_3, 0) + coalesce(monto_mes_cex_3, 0)) as mes_3"),
                DB::raw("sum(coalesce(monto_mes_ds_4, 0) + coalesce(monto_mes_cex_4, 0)) as mes_4"),
                DB::raw("sum(coalesce(monto_mes_ds_5, 0) + coalesce(monto_mes_cex_5, 0)) as mes_5"),
                DB::raw("sum(coalesce(monto_mes_ds_6, 0) + coalesce(monto_mes_cex_6, 0)) as mes_6"),
                DB::raw("sum(coalesce(monto_mes_ds_7, 0) + coalesce(monto_mes_cex_7, 0)) as mes_7"),
                DB::raw("sum(coalesce(monto_mes_ds_8, 0) + coalesce(monto_mes_cex_8, 0)) as mes_8"),
                DB::raw("sum(coalesce(monto_mes_ds_9, 0) + coalesce(monto_mes_cex_9, 0)) as mes_9"),
                DB::raw("sum(coalesce(monto_mes_ds_10, 0) + coalesce(monto_mes_cex_10, 0)) as mes_10"),
                DB::raw("sum(coalesce(monto_mes_ds_11, 0) + coalesce(monto_mes_cex_11, 0)) as mes_11"),
                DB::raw("sum(coalesce(monto_mes_ds_12, 0) + coalesce(monto_mes_cex_12, 0)) as mes_12")
            ])
            ->where([
                'anio_id' => $anio
            ])
            ->get();

        if (!$DS->isEmpty()) {
            return ['estado' => 'success', 'DSCE' => $DS];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen registros en el año ingresado.'];
        }
    }
}
