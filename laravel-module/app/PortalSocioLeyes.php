<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PortalSocioLeyes extends Model
{
    protected $table = "sec_leyes";

    protected function traerNoticias()
    {
        $noticias = DB::table('sec_leyes')
            ->select([
                'id',
                'titulo',
                'slug',
                'cuerpo',
                'archivo'
            ])
            ->orderBy('id', 'desc')
            ->where([
                'activo' => 'S',
                'sec_leyes_estado' => 1
            ])
            ->paginate(8);
        if (!$noticias->isEmpty()) {
            $ultima = $this->traerUltimaReunion();
            return ['estado' => 'success', 'noticias' => $noticias, 'ultima_noticia' => $ultima['noticia']];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay noticias para mostrar.'];
        }
    }

    protected function traerUltimaReunion()
    {
        $noticia = DB::table('sec_leyes')
            ->select([
                'id',
                'titulo',
                'slug',
                'cuerpo',
                'archivo'
            ])
            ->where([
                'activo' => 'S',
                'sec_leyes_estado' => 1
            ])
            ->latest('id')->first();
        if (!is_null($noticia)) {
            return ['estado' => 'success', 'noticia' => $noticia];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay noticias para mostrar.'];
        }
    }
}
