<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CbeNacimiento extends Model
{
    protected $table = 'cbe_nacimiento';

    protected function insertar($cbe_id, $socio_id)
    {
        $this->socio_id = $socio_id;
        $this->cuenta_bienestar_id = $cbe_id;
        $this->activo = 'S';
        if ($this->save()) {
            return [ 'estado'=>'success', 'mensaje'=> 'Item ingresado correctamente' ];
        }
        return [ 'estado'=>'failed', 'mensaje'=> 'Error al guardar item' ];
    }
}
