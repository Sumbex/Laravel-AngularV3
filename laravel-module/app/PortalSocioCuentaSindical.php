<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\PortalSocio;

class PortalSocioCuentaSindical extends Model
{
    protected $table = "cuenta_sindicato";

    protected  $saldo = 100000;

    protected function socioID()
    {
        return PortalSocio::socioLogeado()->id;
    }

    protected function verificarSocio($id)
    {
        return PortalSocio::verificarSocio($id);
    }

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
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $inicio = $this->traerMontoIncialMensual($anio, $mes);
            if ($inicio['estado'] == 'success') {
                $totales = $this->totalesCuentaSindical($anio, $mes);
                if ($totales['estado'] == 'success') {
                    return $this->traerCuentaSindical($inicio['inicio'][0]->inicio_mensual, $anio, $mes, $totales['totales'][0]);
                } else {
                    return $totales;
                }
            } else {
                return $inicio;
            }
        } else {
            $verificarSocio;
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
                DB::raw("upper(cs.descripcion) as descripcion"),
                //'cs.descripcion',
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
            $totales->cierre_mes = $totales->total + $IM;
            return ['estado' => 'success', 'monto_inicio' => $IM, 'CS' => $return, 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function totalesCuentaSindical($anio, $mes)
    {
        $totales = DB::table('cuenta_sindicato')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as total_ingreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as total_egreso'),
                DB::raw('sum(coalesce(monto_ingreso, 0)) - sum(coalesce(monto_egreso, 0)) as total')
            ])
            ->where([
                'detalle_camping' => null,
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

    protected function traerCajaChicaTotal($anio, $mes)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $existe = $this->existeCajaChica($anio, $mes);
            if ($existe['estado'] == 'success') {
                $totales = $this->totalesCajaChica($anio, $mes);
                if ($totales['estado'] == 'success') {
                    return $this->traerCajaChica($anio, $mes, $existe['monto_caja'], $totales['totales']);
                } else {
                    return $totales;
                }
            } else {
                return $existe;
            }
        } else {
            return $verificarSocio;
        }
    }

    protected function existeCajaChica($anio, $mes)
    {
        $existe = DB::table('cuenta_sindicato')
            ->select([
                'monto_egreso'
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes,
                'tipo_cuenta_sindicato' => 3
            ])
            ->get();

        if (!$existe->isEmpty()) {

            $resta =  $this->saldo - $existe[0]->monto_egreso;
            $TC = $existe[0]->monto_egreso + $resta;

            return ['estado' => 'success', 'monto_caja' => $TC];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no ha realizado el ingreso a Caja Chica este mes.'];
        }
    }

    protected function traerCajaChica($anio, $mes, $MC, $total)
    {
        $caja = DB::table('cs_caja_chica as cc')
            ->select([
                'cc.id',
                DB::raw("concat(cc.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'cc.numero_documento',
                'cc.archivo_documento',
                DB::raw("upper(cc.descripcion) as descripcion"),
                //'cc.descripcion',
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
        $existe = $this->existeCajaChica($anio, $mes);
        if ($existe['estado'] == 'success') {
            $totales = DB::table('cs_caja_chica')
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
        } else {
            return $existe;
        }
    }

    protected function traerCampingTotal($anio, $mes)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $monto = $this->traerMontoCierreCamping($anio, $mes);
            if (array_has($monto, 'estado')) {
                $totales = $this->totalesCamping($anio, $mes, $monto['monto']);
                if ($totales['estado'] == 'success') {
                    return $this->traerCamping($anio, $mes, $monto['monto'], $totales['totales']);
                } else {
                    return $totales;
                }
            }
        } else {
            $verificarSocio;
        }
    }

    protected function traerMontoCierreCamping($anio, $mes)
    {
        $monto = DB::table('monto_cierre_camping')
            ->select([
                'cierre_calculable'
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes
            ])
            ->get();

        if (!$monto->isEmpty()) {
            return ['estado' => 'success', 'monto' => $monto[0]->cierre_calculable];
        } else {
            return ['estado' => 'failed', 'monto' => 0];
        }
    }

    protected function traerCamping($anio, $mes, $MI, $totales)
    {
        $camping = DB::table('cuenta_sindicato as cs')
            ->select([
                'cs.id',
                DB::raw("concat(cs.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'cs.numero_documento',
                'cs.archivo_documento',
                DB::raw("upper(cs.descripcion) as descripcion"),
                //'cs.descripcion',
                'cs.monto_ingreso',
                'cs.monto_egreso',
                'cs.definicion'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->where([
                'cs.anio_id' => $anio,
                'cs.mes_id' => $mes,
                'cs.activo' => 'S',
                'cs.tipo_cuenta_sindicato' => '5',
                'cs.detalle_camping' => 'S'
            ])
            ->orderBy('cs.dia', 'asc')
            ->get();

        if (!$camping->isEmpty()) {
            $tomar = true;

            for ($i = 0; $i < count($camping); $i++) {
                switch ($camping[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $camping[$i]->saldo_actual = $MI + $camping[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $camping[$i]->saldo_actual = $camping[$i - 1]->saldo_actual + $camping[$i]->monto_ingreso;
                        }
                        break;
                    case 2:
                        if ($tomar == true) {
                            $camping[$i]->saldo_actual = $MI - $camping[$i]->monto_egreso;
                            $tomar = false;
                        } else {
                            $camping[$i]->saldo_actual = $camping[$i - 1]->saldo_actual - $camping[$i]->monto_egreso;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }
            return ['estado' => 'success', 'monto_inicio' => $MI, 'camping' => $camping, 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function totalesCamping($anio, $mes, $total)
    {
        $totales = DB::table('cuenta_sindicato')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as total_ingreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as total_egreso'),
                DB::raw('sum(coalesce(monto_ingreso, 0)) - sum(coalesce(monto_egreso, 0)) as total')
            ])
            ->where([
                'anio_id' => $anio,
                'mes_id' => $mes,
                'activo' => 'S',
                'tipo_cuenta_sindicato' => '5',
                'detalle_camping' => 'S'
            ])
            ->get();

        if (!$totales->isEmpty()) {
            $totales[0]->total = $totales[0]->total + $total;
            return ['estado' => 'success', 'totales' => $totales[0]];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function traerMontoInicialGO($anio, $mes)
    {
        $existe = DB::table('cuenta_sindicato')
            ->select([
                'monto_egreso'
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes,
                'tipo_cuenta_sindicato' => 6
            ])
            ->get();

        if (!$existe->isEmpty()) {
            return ['estado' => 'success', 'monto' => $existe[0]->monto_egreso];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existe un monto en este mes.'];
        }
    }

    public function directiva()
    {
        $data = DB::table('directiva')->select('id', 'directiva')->where('activo', 'S')->first();
        return $data;
    }

    protected function traerGastosOperacionales($anio, $mes)
    {
        /* dd($this->totalesGO($anio, $mes)); */
        $directiva = $this->directiva();
        $gastos = DB::table('cs_gastos_operacionales as go')
            ->select([
                'go.id',
                DB::raw("concat(go.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'go.numero_documento',
                'go.archivo_documento',
                DB::raw("upper(go.descripcion) as descripcion"),
                DB::raw('coalesce(go.monto_ingreso, 0) as monto_ingreso'),
                DB::raw('coalesce(go.monto_egreso, 0) as monto_egreso'),
                'go.definicion'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->where([
                'go.activo' => 'S',
                /* 'go.anio_id' => $anio,
                'go.mes_id' => $mes, */
            ])
            ->orderby('a.descripcion', 'asc')
            ->orderby('go.mes_id', 'asc')
            ->orderby('go.dia', 'asc')
            ->get();

        if (!$gastos->isEmpty()) {
            $MC = $this->traerMontoInicialGO($anio, $mes);
            $tomar = true;

            for ($i = 0; $i < count($gastos); $i++) {
                switch ($gastos[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $gastos[$i]->saldo_actual = $MC['monto'] + $gastos[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $gastos[$i]->saldo_actual = $gastos[$i - 1]->saldo_actual + $gastos[$i]->monto_ingreso;
                        }
                        break;
                    case 2:
                        if ($tomar == true) {
                            $gastos[$i]->saldo_actual = $MC['monto'] - $gastos[$i]->monto_egreso;
                            $tomar = false;
                        } else {
                            $gastos[$i]->saldo_actual = $gastos[$i - 1]->saldo_actual - $gastos[$i]->monto_egreso;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }
            $totales = $this->totalesGO($anio, $mes, $MC['monto']);
            /* $total->cierre_mes = $total->total + $MC;
        return ['estado' => 'success', 'monto_inicio' => $MC, 'caja' => $caja, 'total' => $total]; */
            return ['estado' => 'success', 'monto_inicio' => $MC['monto'], 'gastos' => $gastos, 'totales' => $totales['totales']];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function totalesGO($anio, $mes, $MC)
    {
        $totales = DB::table('cs_gastos_operacionales')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as total_ingreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as total_egreso'),
                DB::raw('sum(coalesce(monto_ingreso, 0)) - sum(coalesce(monto_egreso, 0)) as total')
            ])
            ->where([
                'anio_id' => $anio,
                'mes_id' => $mes,
                'activo' => 'S'
            ])
            ->get();

        if (!$totales->isEmpty()) {
            $totales[0]->cierre_mes = $totales[0]->total + $MC;
            return ['estado' => 'success', 'totales' => $totales[0]];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }
}
