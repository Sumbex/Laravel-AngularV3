<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiqHistorialLiquidaciones extends Model
{
    protected $table = 'liq_historial_liquidaciones';
    protected $fillable = ['fecha', 'id_empleado', 'ruta_archivo', 'id_usuario', 'activo'];
}
