<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PortalSocioCuentaSindical extends Model
{
    protected $table = "cuenta_sindicato";

    protected function traerMontoIncialMensual($anio, $mes)
    {
        $inicio = DB::table('c_s_cierre_mensual')
            ->select([
                'inicio_mensual'
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes
            ])
            ->get();
        if (!$inicio->isEmpty()) {
            return ['estado' => 'success', 'inicio' => $inicio];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existe un Inicio mensual en este mes.'];
        }
    }

    protected function traerCuentaSindicalTotal($anio, $mes)
    {
        $inicio = $this->traerMontoIncialMensual($anio, $mes);
        if ($inicio['estado'] == 'success') {
            $totales = $this->totalesCuentaSindical($anio, $mes);
            if ($totales['estado'] == 'success') {
                $CS = $this->traerCuentaSindical($inicio['inicio'][0]->inicio_mensual, $anio, $mes, $totales['totales'][0]);
                if ($CS['estado'] == 'success') {
                    return $CS;
                } else {
                    return $CS;
                }
            } else {
                return $totales;
            }
        } else {
            return $inicio;
        }
    }

    protected function traerCuentaSindical($IM, $anio, $mes, $totales)
    {
        $CS = DB::table('cuenta_sindicato as cs')
            ->select([
                'cs.id',
                DB::raw("concat(cs.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'cs.numero_documento',
                'cs.archivo_documento',
                'cs.descripcion',
                'cs.monto_ingreso',
                'cs.monto_egreso',
                'cs.tipo_cuenta_sindicato',
                'cs.definicion'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->where([
                'cs.activo' => 'S',
                'cs.anio_id' => $anio,
                'cs.mes_id' => $mes,
                'cs.detalle_camping' => null
            ])
            ->orderBy('cs.tipo_cuenta_sindicato', 'asc')
            ->orderBy('cs.dia', 'asc')
            ->get();

        if (!$CS->isEmpty()) {
            $tomar = true;

            for ($i = 0; $i < count($CS); $i++) {
                switch ($CS[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $CS[$i]->saldo_actual = $IM + $CS[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $CS[$i]->saldo_actual = $CS[$i - 1]->saldo_actual + $CS[$i]->monto_ingreso;
                        }
                        break;

                    case 2:
                        if ($tomar == true) {
                            $CS[$i]->saldo_actual = $IM - $CS[$i]->monto_egreso;
                            $tomar = false;
                        } else {
                            $CS[$i]->saldo_actual = $CS[$i - 1]->saldo_actual - $CS[$i]->monto_egreso;
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }

            $return = [];
            $return['fijos'] = [];
            $return['variables'] = [];
            $return['caja_chica'] = [];
            $return['prestamos'] = [];
            $return['camping'] = [];

            foreach ($CS as $key) {
                switch ($key->tipo_cuenta_sindicato) {
                    case 1:
                        $return['fijos'][] = $key;
                        break;

                    case 2:
                        $return['variables'][] = $key;
                        break;

                    case 3:
                        $return['caja_chica'][] = $key;
                        break;

                    case 4:
                        $return['prestamos'][] = $key;
                        break;

                    case 5:
                        $return['camping'][] = $key;
                        break;

                    default:
                        # code...
                        break;
                }
            }
            return ['estado' => 'success', 'monto_inicio' => $IM, 'CS' => $return, 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function totalesCuentaSindical($anio, $mes)
    {
        $totales = DB::table('cuenta_sindicato')
            ->select([
                DB::raw('sum(monto_ingreso) as total_ingreso'),
                DB::raw('sum(monto_egreso) as total_egreso'),
                DB::raw('sum(monto_ingreso) - sum(monto_egreso) as total')
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes,
            ])
            ->get();

        if (!$totales->isEmpty()) {
            return ['estado' => 'success', 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }
}
