<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LiqConfigHaberes extends Model
{
    protected $table = 'liq_config_haberes';


    protected function registrar($r)
    {
        $verify = $this->where([
            'empleado_id' => $r->id_empleado,
            'cs_lista_haberes_id' => $r->id_hab,
            'activo' => 'S'
        ])->first();

        if ($verify) {
            return [
                'estado' => 'failed',
                'mensaje' => 'El empleado ya tiene este haber en la lista'
            ];
        }else{
            $ch = $this;
            $ch->empleado_id = $r->id_empleado;
            $ch->cs_lista_haberes_id = $r->id_hab;

            switch ($r->tipo) {
                case 'DM':

                    $ch->dias = $r->dias;
                    $ch->monto = ($r->dias * $r->valor); 

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
                    $ch->monto = $r->cargas * $r->valor;
                break;
                case 'P': $ch->porcentaje = $r->valor; break;
                case 'M': $ch->monto = $r->valor; break; 
                default: break;
            }   
            $ch->activo = 'S';

            if ($ch->save()) {
                return [
                    'estado' => 'success',
                    'mensaje' => 'Haber registrado con exito!'
                ];
            }
        }
    }

    //  protected function registrar_DM($r)
    // {
    //     $verify = $this->where([
    //         'empleado_id' => $r->id_empleado,
    //         'cs_lista_haberes_id' => $r->id_hab,
    //         'activo' => 'S'
    //     ])->first();

    //     if ($verify) {
    //         return [
    //             'estado' => 'failed',
    //             'mensaje' => 'El empleado ya tiene este haber en la lista'
    //         ];
    //     }else{
    //         $ch = $this;
    //         $ch->empleado_id = $r->id_empleado;
    //         $ch->cs_lista_haberes_id = $r->id_hab;

    //         switch ($r->tipo) {
    //             case 'P': $ch->porcentaje = $r->valor; break;
    //             case 'M': $ch->monto = $r->valor; break; 
                
    //             default: break;
    //         }   
    //         $ch->activo = 'S';

    //         if ($ch->save()) {
    //             return [
    //                 'estado' => 'success',
    //                 'mensaje' => 'Haber registrado con exito!'
    //             ];
    //         }
    //     }
    // }

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
