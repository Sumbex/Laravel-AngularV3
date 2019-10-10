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
            /* dd( $socios['socios']); */
            DB::beginTransaction();
            foreach ($socios['socios'] as $key) {
                $asistencia = new SecAsistencia;
                $asistencia->reunion_id = $reunion_id;
                $asistencia->socio_id = $key->id;
                $asistencia->estado_asistencia_id = 2;
                $asistencia->activo = 'S';
                /* $asistencia->save();
                $con = $con + 1; */
                if($asistencia->save()){
                    $con = $con + 1;
                }
            }
            /* dd($con); */
            /* dd($con.' / '.count($socios)); */
            /* dd(count($test['socios'])); */
            if ($con == count($socios['socios'])) {
                DB::commit();
                return ['estado' => 'success', 'ingresos' => $con];
            } else {
                DB::rollBack();
                return ['estado' => 'failed'];
            }
        } else {
            return ['estado' => 'failed'];
        }
    }
}
