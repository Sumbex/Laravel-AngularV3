<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class p_salud_retornable extends Model
{
    protected $table = 'p_salud_retornable';


    protected function listar($mes, $anio)
    {
		    $query=DB::select("
		    		SELECT 
					    psr.id as id,
					    psr.prestamo_id as prestamo_id,
					    --concat(p.dia,' de ',m_p.descripcion,',',a_p.descripcion) as fecha_prestamo,
					    concat(psr.dia,' de ',m_psr.descripcion,',',a_psr.descripcion) as fecha_detalle,
					    p.transferencia_bancaria,
					    p.archivo,
					    P.descripcion_prestamo,
					    psr.definicion,
					    p.egreso,
					    COALESCE(psr.ingreso,'0') as ingreso,
					    psr.monto_dia_sueldo,
					    psr.monto_trimestral,
					    psr.monto_termino_conflicto,
					    psr.monto_restante,
					    CONCAT( psr.numero_cuota,'/',psr.cuotas ) as cuotas,
					    psr.estado_prestamo,
					    psr.estado_abono,
					    p.cuotas as cuotas_totales
					from p_salud_retornable as psr
					inner join cs_prestamo as p on p.id = psr.prestamo_id 
					inner join anio as a_p on a_p.id = p.anio_id
					inner join mes as m_p on m_p.id = p.mes_id
					inner join anio as a_psr on a_psr.id = psr.anio_id
					inner join mes as m_psr on m_psr.id = psr.mes_id
					where 
					    (psr.mes_id = $mes and psr.anio_id = $anio) 
					    -- or
					    -- (psr.estado_prestamo = 'vigente' and (p.mes_id <= $mes ) or p.anio_id < $anio )
					    
		    ");


		    if (count($query)>0) {
		    	 return $query;
		    }
		    return null;
    }
}
