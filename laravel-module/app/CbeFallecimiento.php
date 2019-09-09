<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CbeFallecimiento extends Model
{
    protected $table = 'cbe_fallecimiento';

    protected function insertar($cbe_id, $socio_id, $rut)
    {
        $this->socio_id = $socio_id;
        $this->cuenta_bienestar_id = $cbe_id;
        $this->activo = 'S';
        $this->rut_fallecido = $rut;
        if ($this->save()) {
            return [ 'estado'=>'success', 'mensaje'=> 'Item ingresado correctamente' ];
        }
        return [ 'estado'=>'failed', 'mensaje'=> 'Error al guardar item' ];
    }
}
