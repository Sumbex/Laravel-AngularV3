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
            DB::beginTransaction();
            foreach ($socios['socios'] as $key) {
                $asistencia = new SecAsistencia;
                $asistencia->reunion_id = $reunion_id;
                $asistencia->socio_id = $key->id;
                $asistencia->estado_asistencia_id = 2;
                $asistencia->activo = 'S';
                if ($asistencia->save()) {
                    $con = $con + 1;
                }
            }
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

    protected function borrarInasistentesReunionActiva($reunion_id)
    {
        $borrar = SecAsistencia::where([
            'activo' => 'S',
            'reunion_id' => $reunion_id
        ]);

        if ($borrar->delete()) {
            return ['estado' => 'success'];
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function marcarAsitenciaSocio($request)
    {
        $verificarJus = DB::table('sec_justificacion')
            ->where([
                'reunion_id' => $request->reunion_id,
                'socio_id' => $request->socio_id
            ])
            ->get();

        if ($verificarJus->isEmpty()) {
            $socio = DB::table('sec_asistencia')
                ->where([
                    'reunion_id' => $request->reunion_id,
                    'socio_id' => $request->socio_id
                ])
                ->first();
            $socio->estado_asistencia = 1;
            if ($socio->save()) {
                return ['estado' => 'success', 'mensaje' => 'Socio marcado como presente correctamente.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El socio al que intentas marcar como asistido ya se encuentra justificado.'];
        }
    }
}
