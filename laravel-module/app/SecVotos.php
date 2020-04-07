<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SecVotos extends Model
{
    protected $table = "sec_votos";

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

    protected function ingresarVotosNulos($tema_id)
    {
        $socios = $this->traerSociosActivos();
        if ($socios['estado'] == 'success') {
            $count = 0;
            DB::beginTransaction();
            foreach ($socios['socios'] as $key) {
                $voto = new SecVotos;
                $voto->tema_id = $tema_id;
                $voto->socio_id = $key->id;
                $voto->voto_id = 4;
                $voto->activo = "S";
                if ($voto->save()) {
                    $count++;
                }
            }
            if ($count == count($socios['socios'])) {
                DB::commit();
                return ['estado' => 'success'];
            } else {
                DB::rollBack();
                return ['estado' => 'failed'];
            }
        } else {
            return $socios;
        }
    }
}
