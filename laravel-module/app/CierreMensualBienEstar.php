<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CierreMensualBienEstar extends Model
{
     protected $table = "cbe_cierre_mensual";


   protected function listar_cierre_mensual_cbe($anio, /*$mes,*/ $cuenta)
    {
    	if ($cuenta == '2') { //cuenta bienestar
    		$data = DB::select('SELECT 
								cs_cm.id, 
								a.id as anio_id,
							    a.descripcion as anio,
							    m.id as mes_id,
							    m.descripcion as mes,
							    cs_cm.inicio_mensual,
							    cs_cm.cierre_mensual
							from cbe_cierre_mensual as cs_cm
							inner join anio as a on a.id = cs_cm.anio_id
							inner join mes as m on m.id = cs_cm.mes_id
							where cs_cm.activo = ?
							and cs_cm.anio_id = ?
							order by m.id asc
						', ['S', $anio/*, $mes*/ ]);

    		foreach ($data as $key) {

    			//$key->inicio_mensual = number_format( $key->inicio_mensual , 0, '.', ',');
    			$key->inicio_mensual_m = (number_format( $key->inicio_mensual , 0, '.', ','));
    			if ($key->cierre_mensual != null) {
    				$key->cierre_mensual_m = (number_format( $key->cierre_mensual , 0, '.', ','));
    			}
    			if ($key->cierre_mensual == null) {
    					$s_a = $key->inicio_mensual; //saldo actual de mes <

    					$listar = Cuentasindicato::where(['anio_id' => $key->anio_id, 'mes_id' => $key->mes_id])->get();
						$tomar = true;
						$ultimo_valor =0;
						for ($i=0; $i < count($listar); $i++) { 
						
							switch ($listar[$i]->definicion) {
								case '1':  
									if ($tomar == true) {
										$listar[$i]->saldo_actual_raw = $s_a + $listar[$i]->monto_ingreso;
										$ultimo_valor = $listar[$i]->saldo_actual_raw;
										$tomar = false;
									}else{
										$listar[$i]->saldo_actual_raw = $listar[$i-1]->saldo_actual_raw  + $listar[$i]->monto_ingreso;
										$ultimo_valor = $listar[$i]->saldo_actual_raw;
									}
								break;
								case '2':  
									if ($tomar == true) {
										$listar[$i]->saldo_actual_raw = $s_a - $listar[$i]->monto_egreso;
										$ultimo_valor = $listar[$i]->saldo_actual_raw;
										$tomar = false;
									}else{
										
										$listar[$i]->saldo_actual_raw = $listar[$i-1]->saldo_actual_raw - $listar[$i]->monto_egreso;
										$ultimo_valor = $listar[$i]->saldo_actual_raw;
									}
								break;
							}
						}
    				$key->cierre_mensual_m = number_format($ultimo_valor, 0, '.', ','). "(*)";
    			}
    			
    		}
    		return $data;
    	}
    }


    protected function inicio_mensual($anio, $mes)
    {
    	$val = $this->select('inicio_mensual')->where([
    		'mes_id' => $mes, 'anio_id' => $anio
    	])->first();

    	if ($val) {
    		return ['estado' => 'success', $val];
    	}
    	return ['estado'=>'failed', 'mensaje' => 'no hay monto incial en este mes'];
    }# code...
    
}
