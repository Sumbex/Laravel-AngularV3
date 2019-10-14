<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SecJustificacion extends Model
{
    protected $table = "sec_justificacion";

    protected function traerJustificacionSocio($reunion_id, $socio_id)
    {
        $justificacion = DB::table('sec_justificacion as sj')
            ->select([
                'sj.id',
                'sj.descripcion',
                'stj.descripcion as tipo'
            ])
            ->join('sec_tipo_justificacion as stj', 'stj.id', 'sj.tipo_justificacion_id')
            ->where([
                'sj.activo' => 'S',
                'sj.reunion_id' => $reunion_id,
                'sj.socio_id' => $socio_id
            ])
            ->get();

        if (!$justificacion->isEmpty()) {
            return ['estado' => 'success', 'justificacion' => $justificacion[0]];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El socio que estas seleccionando no tiene una justificacion.'];
        }
    }
}
