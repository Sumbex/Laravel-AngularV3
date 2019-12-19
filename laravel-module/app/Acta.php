<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    protected $table = 'sec_actas';

    protected function insertar($r)
    {
        $a = $this;
        $a->socio_crea = Auth::user()->id;
        $a->encabezado = "En ".$r->ubicacion." a ".$r->fecha." siendo las ".$r->hora.". Se da inicio a la asamblea general de socios siendo dirigida por el ".$r->dirigente.", se hace lectura al Acta anterior la que es aprobada sin observaciones.";
        $a->miembros = $r->miembros;
        $a->orden_dia = $r->orden_dia;
        $a->detalle_orden_dia = $r->detalle_orden_dia;
        $a->fecha = $r->fecha;
        $a->hora = $r->hora;
        $a->ubicacion = $r->ubicacion;
        $a->dirigente = $r->dirigente;
        $a->activo = 'S';
        $a->estado = 1;
        if ($a->save()) {
            return [
                'estado' =>'success',
                'mensaje' => 'Acta registrada con exito!'
            ];
        }else{
            return [
                'estado' =>'failed',
                'mensaje' => 'Error al registrar'
            ];
        }
    }

    public function listar()
    {
        $listar = Acta::all();

        if ($listar) {
            return response()->json($listar);
        }
    }

    public function traer($id)
    {
        # code...
    }


}
