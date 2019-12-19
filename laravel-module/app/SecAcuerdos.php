<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecAcuerdos extends Model
{
    protected $table = "sec_acuerdos";
    protected $fillable = ['titulo', 'fecha', 'contenido', 'descripcion_cambio_estado', 'id_tipo_acuerdo', 'id_estado_acuerdo', 'id_usuario', 'id_activo'];
}
