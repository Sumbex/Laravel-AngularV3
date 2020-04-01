<?php

namespace App;

use App\LiqConfigHaberes;
use App\LiqConfigDescuentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LiqConfigDescuentos extends Model
{
    protected $table = 'liq_config_descuentos';


    public function monto_feriado_proporcional($empleado_id){

        $get = LiqConfigHaberes::where([
            'activo'=>'S',
            'empleado_id' => $empleado_id,
            'cs_lista_haberes_id' => 11// feriado proporcional
        ])->first();
        if ($get) {
            return (int)$get->monto;
        }else{
            return 0;
        }

    }

    protected function registrar($r)
    {
        $verify = $this->where([
            'empleado_id' => $r->id_empleado,
            'cs_lista_descuentos_id' => $r->id_desc,
            'activo' => 'S'
        ])->first();

        if ($verify) { // si existe registro, entonces actualizar montos
                switch ($r->tipo) {
                case 'P': 
                    $total_imp = $this->total_imponible($r->id_empleado);
                    $verify->porcentaje = $r->valor; 
                    $verify->monto = round(($r->valor / 100) * $total_imp); 
                   
                    // si el item es feriado prop desde descuentos
                    if($verify->cs_lista_descuentos_id == "1"||
                       $verify->cs_lista_descuentos_id == "2"||
                       $verify->cs_lista_descuentos_id == "4"
                    ){
                        
                        //en esta consulta hacemos el calculo con los 3 items (afp, salud, cesantia)
                        $fp = $this->monto_feriado_proporcional($r->id_empleado);
                        $fer_prop=DB::select("SELECT
                            coalesce(round($fp - sum(valor)) , 0) valor
                            from(SELECT 
                                cs_lista_descuentos_id,
                                porcentaje,
                                monto,
                                ($fp * (porcentaje / 100)) valor                                        
                            FROM liq_config_descuentos des
                            inner join cs_lista_descuentos lh on lh.id = des.cs_lista_descuentos_id
                            where empleado_id = $r->id_empleado and des.activo = 'S' and cs_lista_descuentos_id in (1,2,4)) x");

                        if(count($fer_prop) > 0){
                            
                                //en esta consulta verificamos si existe el ite feriados prop desde descuentos
                                $des_very = LiqConfigDescuentos::where([
                                    'activo'=>'S',
                                    'empleado_id'=> $r->id_empleado,
                                    'cs_lista_descuentos_id' => 8 //feriado prop desde descuentos
                                ])->first();

                                if ($des_very) {
                                   
                                    $des_very->monto = ceil($fer_prop[0]->valor);
                                    $des_very->save();
                                }else{
                                   
                                    //si no existe, creamos el item
                                    $des = new LiqConfigDescuentos;
                                    $des->empleado_id = $r->id_empleado;
                                    $des->cs_lista_descuentos_id = 8; //feriado prop desde descuentos
                                    $des->monto = ceil($fer_prop[0]->valor);
                                    $des->activo = 'S';
                                    $des->save();
                                }
                            }
                    }
                
                break;
                case 'M': $verify->monto = $r->valor; break; 
                default: break;
            }   
            $verify->activo = 'S';

            if ($verify->save()) {
                return [
                    'estado' => 'success',
                    'mensaje' => 'Descuento actualizado con exito!'
                ];
            }
            // return [
            //     'estado' => 'failed',
            //     'mensaje' => 'El empleado ya tiene este descuento en la lista'
            // ];
        }else{
            $total_imp = $this->total_imponible($r->id_empleado);
            $ch = $this;
            $ch->empleado_id = $r->id_empleado;
            $ch->cs_lista_descuentos_id = $r->id_desc;

            switch ($r->tipo) {
                case 'P': 
                    $ch->porcentaje = $r->valor; 
                    $ch->monto = round(($r->valor / 100) * $total_imp); 

                    // si el item es % prop desde descuentos
                    if($ch->cs_lista_descuentos_id == "1"||
                       $ch->cs_lista_descuentos_id == "2"||
                       $ch->cs_lista_descuentos_id == "4"
                    ){
                       
                        //en esta consulta hacemos el calculo con los 3 items (afp, salud, cesantia)
                        $fp = $this->monto_feriado_proporcional($r->id_empleado);
                        $fer_prop=DB::select("SELECT
                            coalesce(round($fp - sum(valor)) , 0) valor
                            from(SELECT 
                                cs_lista_descuentos_id,
                                porcentaje,
                                monto,
                                ($fp * (porcentaje / 100)) valor                                        
                            FROM liq_config_descuentos des
                            inner join cs_lista_descuentos lh on lh.id = des.cs_lista_descuentos_id
                            where empleado_id = $r->id_empleado and des.activo = 'S' and cs_lista_descuentos_id in (1,2,4)) x");

                        if(count($fer_prop) > 0){
                            
                                //en esta consulta verificamos si existe el ite feriados prop desde descuentos
                                $des_very = LiqConfigDescuentos::where([
                                    'activo'=>'S',
                                    'empleado_id'=> $r->id_empleado,
                                    'cs_lista_descuentos_id' => 8 //feriado prop desde descuentos
                                ])->first();

                                if ($des_very) {
                                    
                                    $des_very->monto = ceil($fer_prop[0]->valor);
                                    $des_very->save();
                                }else{
                                    
                                    //si no existe, creamos el item
                                    $des = new LiqConfigDescuentos;
                                    $des->empleado_id = $r->id_empleado;
                                    $des->cs_lista_descuentos_id = 8; //feriado prop desde descuentos
                                    $des->monto = ceil($fer_prop[0]->valor);
                                    $des->activo = 'S';
                                    $des->save();
                                }
                            }
                    }
                
                
                break;
                case 'M': $ch->monto = $r->valor; break; 
                default: break;
            }   
            $ch->activo = 'S';

            if ($ch->save()) {
                return [
                    'estado' => 'success',
                    'mensaje' => 'Descuento registrado con exito!'
                ];
            }
        }
    }

    protected function listar($empleado)
    {
        $listar = DB::select("SELECT 
                                ch.id,
                                lh.descripcion,
                                lh.tipo,
                                ch.monto,
                                ch.porcentaje
                            FROM liq_config_descuentos ch
                            inner join cs_lista_descuentos lh on lh.id = ch.cs_lista_descuentos_id
                            where empleado_id = $empleado and ch.activo = 'S'
        ");

        if (count($listar) > 0) {
            return [
                'estado' => 'success',
                'lista' => $listar
            ];
        }
        return [
            'estado' => 'failed',
            'lista' => null
        ];
    }

    protected function tabla_i_h($empleado)
    {
        $sueldo_base = DB::table('liq_config_haberes')->where([
            'activo'=>'S',
            'cs_lista_haberes_id' => 1,
            'empleado_id' => $empleado

        ])->first();

        $sum_i = DB::select("SELECT 
                                sum(monto) suma
                            FROM liq_config_haberes ch
                            inner join cs_lista_haberes lh on lh.id = ch.cs_lista_haberes_id
                            where empleado_id = $empleado and ch.activo = 'S' and tipo_haber = 'I' ");
        
        $sum_h = DB::select("SELECT 
                                sum(monto) suma
                            FROM liq_config_haberes ch
                            inner join cs_lista_haberes lh on lh.id = ch.cs_lista_haberes_id
                            where empleado_id = $empleado and ch.activo = 'S' and tipo_haber = 'H' ");

            return [
                'estado' => 'success',
                // 'lista_i' => $listar_imp,
                // 'lista_h' => $listar_hab,
                'suma_i' => $sum_i[0]->suma,
                'suma_h' => $sum_h[0]->suma,
                'sueldo_base'=> ($sueldo_base)?$sueldo_base : 0,
            ];
    }

    public function sueldo_base($empleado)
    {
        $sueldo_base = DB::table('liq_config_haberes')->where([
            'activo'=>'S',
            'cs_lista_haberes_id' => 1,
            'empleado_id' => $empleado

        ])->first();

        if ($sueldo_base) {
            return $sueldo_base->monto;
        }
        return 0;
    }

    public function total_imponible($empleado)
    {
        $sum_i = DB::select("SELECT 
                                sum(monto) suma
                            FROM liq_config_haberes ch
                            inner join cs_lista_haberes lh on lh.id = ch.cs_lista_haberes_id
                            where empleado_id = $empleado and ch.activo = 'S' and tipo_haber = 'I' ");
        
        if (count($sum_i)>0) {
            return $sum_i[0]->suma;
        }
        return 0;
    }
}
