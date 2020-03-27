<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LiqConfigDescuentos extends Model
{
    protected $table = 'liq_config_descuentos';


    protected function registrar($r)
    {
        $verify = $this->where([
            'empleado_id' => $r->id_empleado,
            'cs_lista_descuentos_id' => $r->id_desc,
            'activo' => 'S'
        ])->first();

        if ($verify) {
            return [
                'estado' => 'failed',
                'mensaje' => 'El empleado ya tiene este descuento en la lista'
            ];
        }else{
            $total_imp = $this->total_imponible($r->id_empleado);
            $ch = $this;
            $ch->empleado_id = $r->id_empleado;
            $ch->cs_lista_descuentos_id = $r->id_desc;

            switch ($r->tipo) {
                case 'P': 
                    $ch->porcentaje = $r->valor; 
                    $ch->monto = round(($r->valor / 100) * $total_imp); 
                
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
