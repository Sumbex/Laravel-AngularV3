<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CierreMensualSindical extends Model
{
    protected $table = "c_s_cierre_mensual";



    protected function listar_cierre_mensual_cs($anio, /*$mes,*/ $cuenta)
    {
    	if ($cuenta == '1') { //cuenta sindicato
    		$data = DB::select('SELECT 
								cs_cm.id, 
								a.id as anio_id,
							    a.descripcion as anio,
							    m.id as mes_id,
							    m.descripcion as mes,
							    cs_cm.inicio_mensual,
							    cs_cm.cierre_mensual
							from c_s_cierre_mensual as cs_cm
							inner join anio as a on a.id = cs_cm.anio_id
							inner join mes as m on m.id = cs_cm.mes_id
							where cs_cm.activo = ?
							and cs_cm.anio_id = ?', ['S', $anio/*, $mes*/ ]);

    		foreach ($data as $key) {
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
    				$key->cierre_mensual = $ultimo_valor. "kkck";
    			}
    		}
    		return $data;
    	}
    }
}