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

    protected function listar($empleado)
    {
        $listar = DB::select("SELECT 
                                ch.id,
                                lh.descripcion,
                                lh.tipo,
                                ch.monto,
                                ch.porcentaje
                            FROM liq_config_haberes ch
                            inner join cs_lista_haberes lh on lh.id = ch.cs_lista_haberes_id
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
}
