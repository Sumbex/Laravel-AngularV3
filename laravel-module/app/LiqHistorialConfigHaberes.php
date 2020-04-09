<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiqHistorialConfigHaberes extends Model
{
    protected $table = 'liq_hist_haberes';
    protected $fillable = ['liquidacion_id', 'cs_lista_haberes_id', 'porcentaje', 'monto','dias', 'cargas', 'horas', 'activo'];
}
