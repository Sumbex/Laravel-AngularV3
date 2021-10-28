<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SecVotos extends Model
{
    protected $table = "sec_votos";

    protected function traerSociosActivos($tipo)
    {
        $socios;
        switch ($tipo) {
            case 1:
                $socios = DB::table('socios as s')
                ->select([
                    's.id',
                    's.rut',
                    'sdb.rol_turno'
                ])
                ->join('socios_datos_basicos as sdb', 'sdb.socio_id', 's.id')
                ->whereNotNull('sdb.rol_turno')
                ->whereIn('sdb.rol_turno', ['1', '2','3','4'])
                ->where([
                    's.activo' => 'S',
                    's.fecha_egreso' => null,
                ])
                ->get();
                break;
            case 2:
                $socios = DB::table('socios as s')
                ->select([
                    's.id',
                    's.rut',
                    'sdb.rol_turno'
                ])
                ->join('socios_datos_basicos as sdb', 'sdb.socio_id', 's.id')
                ->whereNotNull('sdb.rol_turno')
                ->where([
                    's.activo' => 'S',
                    's.fecha_egreso' => null,
                    'sdb.rol_turno' => '5'
                ])
                ->get();
                break;
            case 3:
                $socios = DB::table('socios as s')
                ->select([
                    's.id',
                    's.rut',
                ])
                ->where([
                    's.activo' => 'S',
                    's.fecha_egreso' => null,
                ])
                ->get();
                break;
            default:
                break;
        }
        if (!$socios->isEmpty()) {
            return ['estado' => 'success', 'socios' => $socios];
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function ingresarVotosNulos($tema_id, $tipo)
    {
        $socios = $this->traerSociosActivos($tipo);
        if ($socios['estado'] == 'success') {
            $count = 0;
            DB::beginTransaction();
            foreach ($socios['socios'] as $key) {
                $voto = new SecVotos;
                $voto->tema_id = $tema_id;
                $voto->socio_id = bcrypt($key->id);
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
