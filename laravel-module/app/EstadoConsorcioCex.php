<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoConsorcioCex extends Model
{
    protected $table = 'estado_consorcio_cuota_extra';

    protected function insertar($socio_id, $mes_id, $anio_id)
    {
        $verif = $this->where([
            'socio_id' => $socio_id
        ])->first();

        if ($verif) {
                $verif->anio_id = $anio_id;
                $verif->mes_id = $mes_id;
                $verif->descripcion = 'cancelado';
                if ($verif->save()) {
                    return true;
                }
                return false;
        }
        else{
            $est = $this;
            $est->socio_id = $socio_id;
            $est->anio_id = $anio_id;
            $est->mes_id = $mes_id;
            $est->descripcion = 'cancelado';
            if ($est->save()) {
                return true;
            }
            return false;

        }
    }
}
