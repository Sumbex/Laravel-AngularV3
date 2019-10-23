<?php

namespace App;

use App\PortalSocio;
use App\SecAsistencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PortalSocioSecretaria extends Model
{
    protected function socioID()
    {
        return PortalSocio::socioLogeado()->id;
    }

    protected function verificarSocio($id)
    {
        return PortalSocio::verificarSocio($id);
    }

    protected function traerReunion()
    {
        $reunion = DB::table('sec_reuniones as sr')
            ->select([
                'sr.id',
                'sr.fecha_inicio',
                'sr.fecha_termino',
                'sr.cabeza as titulo',
                'sr.cuerpo as descripcion',
                DB::raw("concat(u.nombres,' ',u.a_paterno,' ',u.a_materno) as creada_por"),
                'sr.tipo_reunion_id as tipo_id',
                'str.descripcion as tipo',
                'sr.estado_reunion_id as estado'
            ])
            ->join('users as u', 'u.id', 'sr.user_id')
            ->join('sec_tipo_reunion as str', 'str.id', 'sr.tipo_reunion_id')
            ->where([
                'sr.activo' => 'S'
            ])
            ->latest('id')->first();
        /* dd($reunion); */
        if (!is_null($reunion)) {
            if ($reunion->estado == 1 || $reunion->estado == 2 || $reunion->estado == 3 || $reunion->estado == 4) {
                return ['estado' => 'success', 'reunion' => $reunion];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'La reunion ha finalizado, si deseas ver el detalle consulta el historial.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no existen reuniones organizadas.'];
        }
    }

    protected function traerListaReunion($reunion_id)
    {
        return SecAsistencia::traerListaReunion($reunion_id);
    }

    protected function traerHistorialReuniones()
    {
        $reuniones = DB::table('sec_reuniones as sr')
            ->select([
                'sr.id',
                'sr.fecha_inicio',
                'sr.fecha_termino',
                'sr.cabeza as titulo',
                'sr.cuerpo as descripcion',
                DB::raw("concat(u.nombres,' ',u.a_paterno,' ',u.a_materno) as creada_por"),
                'sr.tipo_reunion_id as tipo_id',
                'str.descripcion as tipo',
                'sr.estado_reunion_id as estado'
            ])
            ->join('users as u', 'u.id', 'sr.user_id')
            ->join('sec_tipo_reunion as str', 'str.id', 'sr.tipo_reunion_id')
            ->where([
                'sr.activo' => 'S'
            ])
            ->whereIn('sr.estado_reunion_id', [5, 6])
            ->orderBy('sr.fecha_inicio', 'desc')
            ->take(5)->get();

        if (!$reuniones->isEmpty()) {
            return ['estado' => 'success', 'reuniones' => $reuniones];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no existen reuniones archivadas.'];
        }
    }
}
