<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cuentasindicato extends Model
{
    protected $table = "cuenta_sindicato as cs";


    protected function traer($anio, $mes)
    {
    	$listar = $this::select([
    						'cs.id',
    						DB::raw("concat(cs.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    						'cs.numero_documento',
    						'cs.archivo_documento',
    						'cs.tipo_cuenta_sindicato',
    						'cs.descripcion',
    						'cs.monto_ingreso',
    						'cs.monto_egreso',
    						'cs.definicion'
    					])
    				   ->join('anio as a','a.id','anio_id')
    				   ->join('mes as m','m.id','mes_id')
				       ->where([
							'cs.activo'=>'S',
							'cs.anio_id' => $anio, 
							'cs.mes_id' => $mes,
							])->orderBy('tipo_cuenta_sindicato','asc')->get();
		return $listar;
    }

    protected function resultado_cuenta_sindical($anio, $mes)
	{
			$ingreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			->sum('monto_ingreso');
			$egreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			->sum('monto_egreso');
			$total = $ingreso_total - $egreso_total;

			$anterior = DB::table('c_s_cierre_mensual')->select('inicio_mensual')->where([
				'mes_id'=> $mes, 'anio_id' => $anio
			])->first();

			return [
				'ingreso_total' => $ingreso_total,
				'egreso_total' => $egreso_total,
				'total_actual' => $total,
				'total_resumen' => $total.' + '. $anterior->inicio_mensual.' = '.($total + $anterior->inicio_mensual)
			];

	}
}
