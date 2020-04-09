<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiqHistorialConfigDescuentos extends Model
{
    protected $table = 'liq_hist_descuentos';
    protected $fillable = ['liquidacion_id', 'cs_lista_descuentos_id', 'porcentaje', 'monto', 'activo'];
}
