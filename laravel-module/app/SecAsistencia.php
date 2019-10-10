<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SecAsistencia extends Model
{
    protected $table = "sec_asistencia";

    protected function traerSociosActivos()
    {
        $socios = DB::table('socios')
            ->select([
                'id',
                'rut'
            ])
            ->where([
                'activo' => 'S',
                'fecha_egreso' => null
            ])
            ->get();

        if (!$socios->isEmpty()) {
            return ['estado' => 'success', 'socios' => $socios];
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function ingresarInasistentesReunionActiva($reunion_id)
    {
        $socios = $this->traerSociosActivos();
        if ($socios['estado'] == 'success') {
            $con = 0;
            /* dd( $socios['socios'][5]); */
            DB::beginTransaction();
            for ($i = 0; $i < count($socios['socios']); $i++) {
                /* dd($socios[$i]); */
                $asistencia = new SecAsistencia;
                $asistencia->reunion_id = $reunion_id;
                $asistencia->socio_id = $socios['socios'][$i]->id;
                $asistencia->estado_asistencia_id = 2;
                $asistencia->activo = 'S';
                if ($asistencia->save()) {
                    $con = $con + 1;
                } else {
                    DB::rollBack();
                    return ['estado' => 'failed'];
                }
            }
            if ($con == count($socios)) {
                return ['estado' => 'success'];
                DB::commit();
            } else {
                DB::rollBack();
                return ['estado' => 'failed'];
            }
        } else {
            return ['estado' => 'failed'];
        }
    }
}
