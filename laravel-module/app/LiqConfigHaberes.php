<?php

namespace App;

use App\LiqConfigDescuentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class LiqConfigHaberes extends Model
{
    protected $table = 'liq_config_haberes';


    protected function registrar($r)
    {
        try{
            $verify = $this->where([
                'empleado_id' => $r->id_empleado,
                'cs_lista_haberes_id' => $r->id_hab,
                'activo' => 'S'
            ])->first();

            if ($verify) {

                    switch ($r->tipo) {
                        case 'DM':

                            $verify->dias = $r->dias;
                            $verify->monto = ceil($r->dias * $r->valor); 

                        break;

                        case 'DPM':
                            $sueldo_base = DB::table('liq_config_haberes')->where([
                                'activo'=>'S',
                                'cs_lista_haberes_id' => 1,
                                'empleado_id' => $r->id_empleado

                            ])->first();
                            $verify->horas = $r->horas;
                            $verify->porcentaje = $r->porcentaje;
                            $verify->monto = ceil($r->horas * $r->porcentaje * $sueldo_base->monto); 
                        break;

                        case 'CM':
                            $verify->cargas = $r->cargas;
                            $verify->monto = ceil($r->cargas * $r->valor);
                        break;
                        case 'SBP': //cambiar a futuro a PSB de porcentaje * sueldo base 
                            $sueldo_base = DB::table('liq_config_haberes')->where([
                                'activo'=>'S',
                                'cs_lista_haberes_id' => 1,
                                'empleado_id' => $r->id_empleado

                            ])->first();
                            $verify->porcentaje = $r->valor;
                            $verify->monto = ceil($sueldo_base->monto * ($r->valor / 100));
                        
                        break;
                        case 'M': $verify->monto = ceil($r->valor); break; 
                        default: break;
                    }  
                    if ($verify->save()) {

                        if ($r->id_hab == "11") { // si el id del haber es 11 o sea feriado proporcional
                                //calcular descuento de salud-afp y cesantia
                                // print_r("si 1");
                                $fer_prop=DB::select("SELECT
                                    coalesce(round($verify->monto - sum(valor)) , 0) valor
                                    from(SELECT 
                                        cs_lista_descuentos_id,
                                        porcentaje,
                                        monto,
                                        ($verify->monto * (porcentaje / 100)) valor
                                                        
                                    FROM liq_config_descuentos des
                                    inner join cs_lista_descuentos lh on lh.id = des.cs_lista_descuentos_id
                                    where empleado_id = $r->id_empleado and des.activo = 'S' and cs_lista_descuentos_id in (1,2,4)) x");

                                if(count($fer_prop) > 0){
                                    // print_r("si 2");
                                    $des_very = LiqConfigDescuentos::where([
                                        'activo'=>'S',
                                        'empleado_id'=> $r->id_empleado,
                                        'cs_lista_descuentos_id' => 8 //feriado prop desde descuentos
                                    ])->first();

                                    if ($des_very) {
                                        // print_r("si 3");
                                        $des_very->monto = ceil($fer_prop[0]->valor);
                                        $des_very->save();
                                    }else{
                                        // print_r("si 4");
                                        $des = new LiqConfigDescuentos;
                                        $des->empleado_id = $r->id_empleado;
                                        $des->cs_lista_descuentos_id = 8; //feriado prop desde descuentos
                                        $des->monto = ceil($fer_prop[0]->valor);
                                        $des->activo = 'S';
                                        $des->save();
                                    }
                                }
                        }
                        return [
                            'estado' => 'success',
                            'mensaje' => 'Item actualizado con exito!'
                        ];
                    }
                // return [
                //     'estado' => 'failed',
                //     'mensaje' => 'El empleado ya tiene este haber en la lista'
                // ];
            }else{
                $ch = $this;
                $ch->empleado_id = $r->id_empleado;
                $ch->cs_lista_haberes_id = $r->id_hab;

                switch ($r->tipo) {
                    case 'DM':

                        $ch->dias = $r->dias;
                        $ch->monto = ceil($r->dias * $r->valor); 

                    break;

                    case 'DPM':
                        $sueldo_base = DB::table('liq_config_haberes')->where([
                            'activo'=>'S',
                            'cs_lista_haberes_id' => 1,
                            'empleado_id' => $r->id_empleado

                        ])->first();
                        $ch->horas = $r->horas;
                        $ch->porcentaje = $r->porcentaje;
                        $ch->monto = ceil($r->horas * $r->porcentaje * $sueldo_base->monto); 
                    break;

                    case 'CM':
                        $ch->cargas = $r->cargas;
                        $ch->monto = ceil($r->cargas * $r->valor);
                    break;
                    case 'SBP': //cambiar a futuro a PSB de porcentaje * sueldo base 
                        $sueldo_base = DB::table('liq_config_haberes')->where([
                            'activo'=>'S',
                            'cs_lista_haberes_id' => 1,
                            'empleado_id' => $r->id_empleado

                        ])->first();
                        $ch->porcentaje = $r->valor;
                        $ch->monto = ceil($sueldo_base->monto * ($r->valor / 100));
                    
                    break;
                    case 'M': $ch->monto = ceil($r->valor); break; 
                    default: break;
                }   
                $ch->activo = 'S';

                if ($ch->save()) {

                    if ($r->id_hab == "11") { // si el id del haber es 11 o sea feriado proporcional
                                //calcular descuento de salud-afp y cesantia
                                // print_r("si 1");
                                $fer_prop=DB::select("SELECT
                                        coalesce(round($ch->monto - sum(valor)) , 0) valor
                                    from(SELECT 
                                        cs_lista_descuentos_id,
                                        porcentaje,
                                        monto,
                                        ($ch->monto * (porcentaje / 100)) valor
                                                        
                                    FROM liq_config_descuentos des
                                    inner join cs_lista_descuentos lh on lh.id = des.cs_lista_descuentos_id
                                    where empleado_id = $r->id_empleado and des.activo = 'S' and cs_lista_descuentos_id in (1,2,4)) x");

                                if(count($fer_prop) > 0){
                                    // print_r("si 2");
                                    $des_very = LiqConfigDescuentos::where([
                                        'activo'=>'S',
                                        'empleado_id'=> $r->id_empleado,
                                        'cs_lista_descuentos_id' => 8 //feriado prop desde descuentos
                                    ])->first();

                                    if ($des_very) {
                                        // print_r("si 3");
                                        $des_very->monto = ceil($fer_prop[0]->valor);
                                        $des_very->save();
                                    }else{
                                        // print_r("si 4");
                                        $des = new LiqConfigDescuentos;
                                        $des->empleado_id = $r->id_empleado;
                                        $des->cs_lista_descuentos_id = 8; //feriado prop desde descuentos
                                        $des->monto = ceil($fer_prop[0]->valor);
                                        $des->activo = 'S';
                                        $des->save();
                                    }
                                }
                        }

                    return [
                        'estado' => 'success',
                        'mensaje' => 'Item registrado con exito!'
                    ];
                }
            }
        }
		catch(QueryException $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido seguir con el proceso',
				'exception' => $e //este es solo pal desarrollador;
			];
		}
		catch(\Exception $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido seguir con el proceso',
				'exception' => $e //este es solo pal desarrollador;
			];
		}
    }

    

    protected function listar($empleado)
    {
        $listar_imp = DB::select("SELECT 
                                ch.id,
                                lh.descripcion,
                                lh.tipo,
                                ch.monto,
                                ch.porcentaje,
                                ch.dias,
                                ch.horas,
                                ch.cargas,
                                lh.tipo_haber
                            FROM liq_config_haberes ch
                            inner join cs_lista_haberes lh on lh.id = ch.cs_lista_haberes_id
                            where empleado_id = $empleado and ch.activo = 'S'
                             and tipo_haber = 'I' order by orden  asc
        ");

        $listar_hab = DB::select("SELECT 
                                ch.id,
                                lh.descripcion,
                                lh.tipo,
                                ch.monto,
                                ch.porcentaje,
                                ch.dias,
                                ch.horas,
                                ch.cargas,
                                lh.tipo_haber
                            FROM liq_config_haberes ch
                            inner join cs_lista_haberes lh on lh.id = ch.cs_lista_haberes_id
                            where empleado_id = $empleado and ch.activo = 'S'
                             and tipo_haber = 'H' order by orden  asc
        ");

        $sueldo_base = DB::table('liq_config_haberes')->where([
            'activo'=>'S',
            'cs_lista_haberes_id' => 1,
            'empleado_id' => $empleado

        ])->first();

        if (count($listar_imp) > 0 || count($listar_hab) > 0) {

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
                'lista_i' => $listar_imp,
                'lista_h' => $listar_hab,
                'suma_i' => $sum_i[0]->suma,
                'suma_h' => $sum_h[0]->suma,
                'sueldo_base'=> ($sueldo_base)?$sueldo_base : 0,
            ];
        }
        return [
            'estado' => 'failed',
            'lista' => null
        ];
    }
}
