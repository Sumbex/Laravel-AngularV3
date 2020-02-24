<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Consorciopagodiasueldo extends Model
{
    protected $table = "consorcio_pago_dia_sueldo";


    protected function tabla($directiva)
    {
        $listar = DB::select("SELECT
                                c.id,
                                to_char(fecha,'dd/MM/yyyy') fecha,
                                concat(s.nombres,' ',s.a_paterno,' ',s.a_materno) socio,
                                descripcion,
                                monto,
                                prestamo,
                                numero_documento,
                                documento,
                                (monto + prestamo) total
                            from consorcio_pago_dia_sueldo  c
                            inner join socios s on s.id = c.socio_id
                            where directiva_id =$directiva order by c.fecha desc
        ");

        if (count($listar) > 0) {
            return[
                'estado' => 'success',
                'data' => $listar
            ];
        }
        return [
            'estado' => 'failed',
            'mensaje'=>'No hay datos'
        ];
    }
}
