<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CsPrestamo extends Model
{
    protected $table = 'cs_prestamo';

    protected function prestamos_no_iniciados()
    {
    	$listar = DB::select("
    							SELECT 
								    p.id as prestamo_id,
								    concat(p.dia,' de ',m.descripcion,',',a.descripcion) as fecha_prestamo,
								    descripcion_prestamo,
								    transferencia_bancaria,
								    archivo,
								    egreso,
								    cuotas,
								    case 
								        when tipo_prestamo = 1 then 'Prestamo salud retornable'
								        when tipo_prestamo = 2 then 'Prestamo aporte económico retornable'
								    end as tipo
								from cs_prestamo p 
								inner join mes as m on m.id = p.mes_id
								inner join anio as a on a.id = p.anio_id
								and estado_prestamo = 'vigente' and definicion = 2 
								where p.activo ='S'
    						 ");

    	if (count($listar) > 0) {
    		return ['estado' => 'success', 'body' => $listar];
    	}else{
    		return ['estado'=>'failed','body'=>null];
    	}
    }
}
