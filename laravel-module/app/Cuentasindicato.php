<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cuentasindicato extends Model
{
    protected $table = "cuenta_sindicato";


    protected function traer($anio, $mes)
    {
    	$listar = $this::select([
    						'cuenta_sindicato.id',
    						DB::raw("concat(cuenta_sindicato.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    						'cuenta_sindicato.numero_documento',
    						'cuenta_sindicato.archivo_documento',
    						'cuenta_sindicato.tipo_cuenta_sindicato',
    						'cuenta_sindicato.descripcion',
    						'cuenta_sindicato.monto_ingreso',
    						'cuenta_sindicato.monto_egreso',
    						'cuenta_sindicato.definicion'
    					])
    				   ->join('anio as a','a.id','anio_id')
    				   ->join('mes as m','m.id','mes_id')
				       ->where([
							'cuenta_sindicato.activo'=>'S',
							'cuenta_sindicato.anio_id' => $anio, 
							'cuenta_sindicato.mes_id' => $mes,
							])->orderBy('tipo_cuenta_sindicato','asc')
				       		  ->orderBy('cuenta_sindicato.dia','asc')
				       		  ->get();
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
				'total_resumen' => $total.' + '. $anterior->inicio_mensual.' = '.($total + $anterior->inicio_mensual),
				'total_final' => ($total + $anterior->inicio_mensual)
			];

	}
}
