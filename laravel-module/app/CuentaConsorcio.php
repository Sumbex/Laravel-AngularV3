<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CuentaConsorcio extends Model
{
    protected $table = 'cuenta_consorcio';
    
    protected function listar_formulario()
    {
        $listar = DB::select("SELECT 
                                s.id,
                                s.rut,
                                concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                case when s.fecha_egreso is null then 'vigente'
                                else concat('egresado (',to_char(s.fecha_egreso,'dd-mm-yyyy'),')')
                                end AS estado_socio,
                                
                                case when ec.descripcion is null then 'Sin Registro'
                                else ec.descripcion
                                end AS pago_actual,
                                concat(m.descripcion,' ',a.descripcion) as fecha
                                from socios s
                                left join estado_consorcio ec on ec.socio_id = s.id
                                left join mes m on m.id = ec.mes_id
                                left join anio a on a.id = ec.anio_id
                                order by s.a_paterno, s.a_materno");

        if (count($listar) > 0) {
    		return $listar;
    	}else{
    		return '';
    	}
    }
}
