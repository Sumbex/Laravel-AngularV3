<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class p_apuro_economico_retornable extends Model
{
    protected $table = 'p_apuro_economico_retornable';

    protected function listar($mes, $anio)
    {
    	$query = DB::select("

				SELECT 
						paer.id as id,
						paer.prestamo_id as prestamo_id,
						--concat(p.dia,' de ',m_p.descripcion,',',a_p.descripcion) as fecha_prestamo,
						concat(paer.dia,' de ',m_paer.descripcion,',',a_paer.descripcion) as fecha_detalle,
						p.transferencia_bancaria,
						p.archivo,
						P.descripcion_prestamo,
						paer.definicion,
						p.egreso,
						COALESCE(paer.ingreso,'0') as ingreso,
				        paer.interes_total,
				        paer.interes_mensual,
						paer.monto_restante,
						CONCAT( paer.numero_cuota,'/',paer.cuotas ) as cuotas,
						paer.estado_cuota,
						p.cuotas as cuotas_totales
					from p_apuro_economico_retornable as paer
					inner join cs_prestamo as p on p.id = paer.prestamo_id 
					inner join anio as a_p on a_p.id = p.anio_id
					inner join mes as m_p on m_p.id = p.mes_id
					inner join anio as a_paer on a_paer.id = paer.anio_id
					inner join mes as m_paer on m_paer.id = paer.mes_id
					where 
				     (paer.mes_id = $mes and paer.anio_id = $anio)
							--or
				-- 			((psr.estado_prestamo = 'pagando' or p.estado_prestamo='vigente') and (p.mes_id <= 10) or p.anio_id < 1 )");

    	if (count($query)>0) {
		    	 return $query;
		    }
		    return null;
    }
    protected function listar_nr($mes, $anio)
    {
    	$query = DB::select("SELECT
							    p.id,
							    concat(p.dia,' de ',m.descripcion,',',a.descripcion) as fecha,
							    egreso,
							    transferencia_bancaria,
							    archivo,
							    descripcion_prestamo,
							    estado_prestamo
							from cs_prestamo as p
							inner join mes as m on m.id = p.mes_id
							inner join anio as a on a.id = p.anio_id
							where p.estado_prestamo = 'egresado'
							and anio_id = $anio and mes_id = $mes and p.activo ='S' and tipo_prestamo = 3");

    	if (count($query)>0) {
		    	 return $query;
		    }
		    return null;
    }
}
