<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidaciones extends Model
{
    protected $table = 'liquidacion';
    protected $fillable = ['empleado_id', 'fecha', 'texto_saldo', 'activo'];
}
