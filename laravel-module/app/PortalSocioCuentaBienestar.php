<?php

namespace App;

use App\PortalSocio;
use App\Cuentabienestar;
use App\CajaChicaBienestar;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PortalSocioCuentaBienestar extends Model
{

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
        $inicio = DB::table('cbe_cierre_mensual')
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

    protected function traerCuentaBienestarTotal($anio, $mes)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $inicio = $this->traerMontoIncialMensual($anio, $mes);
            if ($inicio['estado'] == 'success') {
                $totales = $this->totalesCuentaSindical($anio, $mes);
                if ($totales['estado'] == 'success') {
                    return $this->traerCuentaBienestar($inicio['inicio'][0]->inicio_mensual, $anio, $mes, $totales['totales'][0]);
                } else {
                    return $totales;
                }
            } else {
                return $inicio;
            }
        } else {
            return $verificarSocio;
        }
    }

    protected function traerCuentaBienestar($IM, $anio, $mes, $totales)
    {
        $interes = $this->item_interes_a_be($anio, $mes);


        if ($interes != null) { // si existe interes de prestamo
            $verify_interes = DB::table('cuenta_bienestar')->where([
                // 'interes' => 'S', // si es un automatico de interes
                'activo' => 'S',
                'tipo_cuenta_bienestar_id' => '13', //cuenta tipo interes de prestamo
                'anio_id' => $anio,
                'mes_id' => $mes

            ])->first();
            /* dd($verify_interes); */


            if (empty($verify_interes)) {
                $cB = new Cuentabienestar;
                $cB->numero_documento_1 = '--';
                $cB->archivo_documento_1 = '--';
                $cB->tipo_cuenta_bienestar_id = 13; //interes
                $cB->descripcion = $interes['descripcion'];
                $cB->monto_ingreso = $interes['monto_ingreso'];
                $cB->monto_egreso = null;
                // $this->saldo_actual = null;
                $this->definicion = 1;
                // $this->user_crea = Auth::user()->id;
                $cB->activo = 'S';
                $cB->anio_id = $anio;
                $cB->mes_id = $mes;
                $cB->dia = '1';
                // $this->interes = 'S';
                $cB->save();
            } else {
                $vICB = Cuentabienestar::find($verify_interes->id);
                $vICB->monto_ingreso = $interes['monto_ingreso'];
                $vICB->save();
            }
        }

        $bienestar = DB::table('cuenta_bienestar as cb')
            ->select([
                'cb.id',
                DB::raw("concat(cb.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha"),
                'cb.numero_documento_1 as numero_documento',
                'cb.archivo_documento_1 as archivo_1',
                'cb.archivo_documento_2 as archivo_2',
                DB::raw("upper(cb.descripcion) as descripcion"),
                'cb.monto_ingreso',
                'cb.monto_egreso',
                'cb.tipo_cuenta_bienestar_id as tipo',
                'cb.definicion'
            ])
            ->join('anio as a', 'a.id', 'cb.anio_id')
            ->join('mes as m', 'm.id', 'cb.mes_id')
            ->join('tipo_cuenta_bienestar as tcb', 'tcb.id', 'cb.tipo_cuenta_bienestar_id')
            ->where([
                'cb.activo' => 'S',
                'cb.anio_id' => $anio,
                'cb.mes_id' => $mes
            ])
            ->orderBy('tcb.orden', 'asc')
            ->orderBy('cb.dia', 'asc')
            ->get();

        if (!$bienestar->isEmpty()) {
            $tomar = true;



            for ($i = 0; $i < count($bienestar); $i++) {
                switch ($bienestar[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $bienestar[$i]->saldo_actual = $IM + $bienestar[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $bienestar[$i]->saldo_actual = $bienestar[$i - 1]->saldo_actual + $bienestar[$i]->monto_ingreso;
                        }
                        break;

                    case 2:
                        if ($tomar == true) {
                            $bienestar[$i]->saldo_actual = $IM - $bienestar[$i]->monto_egreso;
                            $tomar = false;
                        } else {
                            $bienestar[$i]->saldo_actual = $bienestar[$i - 1]->saldo_actual - $bienestar[$i]->monto_egreso;
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }
            $return = [];
            $return['gas'] = [];
            $return['reunion'] = [];
            $return['votacion'] = [];
            $return['caja_chica'] = [];
            $return['nacimientos'] = [];
            $return['fallecimientos'] = [];
            $return['gastos_medicos'] = [];
            $return['cuota_sindical'] = [];
            $return['elecciones'] = [];
            $return['consorcio'] = [];
            $return['cuota_extra'] = [];
            $return['no_sindicalizados'] = [];
            $return['interes'] = [];

            foreach ($bienestar as $key) {
                switch ($key->tipo) {
                    case 1:
                        $return['gas'][] = $key;
                        break;

                    case 2:
                        $return['reunion'][] = $key;
                        break;

                    case 3:
                        $return['votacion'][] = $key;
                        break;

                    case 6:
                        $return['caja_chica'][] = $key;
                        break;

                    case 5:
                        $return['nacimientos'][] = $key;
                        break;

                    case 4:
                        $return['fallecimientos'][] = $key;
                        break;

                    case 7:
                        $return['gastos_medicos'][] = $key;
                        break;

                    case 8:
                        $return['cuota_sindical'][] = $key;
                        break;

                    case 9:
                        $return['elecciones'][] = $key;
                        break;

                    case 10:
                        $return['consorcio'][] = $key;
                        break;

                    case 11:
                        $return['cuota_extra'][] = $key;
                        break;

                    case 12:
                        $return['no_sindicalizados'][] = $key;
                        break;

                    case 13:
                        $return['interes'][] = $key;
                        break;

                    default:
                        # code...
                        break;
                }
            }
            $totales->cierre_mes = $totales->total + $IM;
            return ['estado' => 'success', 'monto_inicio' => $IM, 'CB' => $return, 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function item_interes_a_be($anio, $mes) //este item esta listo para irse a la cuenta bienestar cuando se llame este metodo
    {

        $get_mes = DB::table('mes as m')->select(['m.descripcion', 'm.id'])->where('id', $mes)->first();
        $get_anio = DB::table('anio as a')->select('a.descripcion')->where('id', $anio)->first();


        if ($get_anio->descripcion == 2020) {

            // dd($get_mes->id);
            if ($get_mes->id >= 6) {

                $list = DB::select("SELECT COALESCE(sum(interes_mensual),0) as interes_mensual  from p_apuro_economico_retornable
								where mes_id = $mes and anio_id = $anio and definicion = 1 and activo = 'S';");

                return [
                    'id' => '',
                    'numero_documento' => '',
                    'fecha' => '',
                    'archivo_documento' => '',
                    'tipo_cuenta_sindicato' => 1,
                    'descripcion' =>  'Aporte de prestamo de apuro económico del mes de ' . $get_mes->descripcion,
                    'monto_ingreso' =>    (int)$list[0]->interes_mensual,
                    'monto_egreso' => '',
                    'definicion' => 1,
                    'saldo_actual_raw' => 0
                ];
            }
        }

        if ($get_anio->descripcion > 2020) {

            if ($get_mes->descripcion > 0) {
                $list = DB::select("SELECT COALESCE(sum(interes_mensual),0) as interes_mensual  from p_apuro_economico_retornable
								where mes_id = $mes and anio_id = $anio and definicion = 1 and activo = 'S';");

                return [
                    'id' => '',
                    'numero_documento' => '',
                    'fecha' => '',
                    'archivo_documento' => '',
                    'tipo_cuenta_sindicato' => 1,
                    'descripcion' =>  'Aporte de prestamo de apuro económico del mes de ' . $get_mes->descripcion,
                    'monto_ingreso' =>    (int)$list[0]->interes_mensual,
                    'monto_egreso' => '',
                    'definicion' => 1,
                    'saldo_actual_raw' => 0
                ];
            }
        }

        return null;
    }

    protected function totalesCuentaSindical($anio, $mes)
    {
        $totales = DB::table('cuenta_bienestar')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as total_ingreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as total_egreso'),
                DB::raw('sum(coalesce(monto_ingreso, 0)) - sum(coalesce(monto_egreso, 0)) as total')
            ])
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes
            ])
            ->get();

        if (!$totales->isEmpty()) {
            return ['estado' => 'success', 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function traerCajaChicaBienestar($anio, $mes)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            return CajaChicaBienestar::traerCajaChicaTotal($anio, $mes);
        } else {
            $verificarSocio;
        }
    }
}
