<?php

namespace App\Http\Controllers;

use App\Cuentabienestar;
use Illuminate\Http\Request;
use App\CierreMensualBienEstar;
use Illuminate\Support\Facades\DB;

class InicioCierreMensualBienestarController extends Controller
{
	
    public function guardar_inicio_mensual(Request $r)
    {
    	if($r->cierre_mensual == 0 || $r->cierre_mensual == ''){//si el calculo del monto es 0
            return ['estado'=>'failed', 'mensaje'=> 'El monto de inicio mensual debe tener un valor'];
        }

    	$existe = DB::table('cbe_cierre_mensual')->where([
    		'activo' => 'S',
    		'anio_id' => $r->anio,
    		'mes_id' => $r->mes,
    	])->first();


    	if (!empty($existe)) { //si existe un monto en este mes
    		$update = DB::table('cbe_cierre_mensual')->where([
    		'activo' => 'S',
    		'anio_id' => $r->anio,
    		'mes_id' => $r->mes,
    		])->update(['inicio_mensual'=>$r->cierre_mensual]);

    		return ['estado'=>'success', 'mensaje'=>'Monto actualizado con exito'];

    	}else{
    		$insert = DB::table('cbe_cierre_mensual')->insert([
	    		'anio_id' => $r->anio,
	    		'mes_id' => $r->mes,
	    		'inicio_mensual' => $r->cierre_mensual,
	    		'activo' => 'S'
	        ]);

	        return ['estado'=>'success', 'mensaje'=>'Monto insertado con exito'];
    	}
	
    }


    public function calcular_cierre_e_inicio_mensual($anio, $mes)
    {

    	$mes = $mes - 1;
    	$existe = DB::table('cbe_cierre_mensual')->where([
    		'activo' => 'S',
    		'anio_id' => $anio,
    		'mes_id' => $mes,
    	])->first();
		


    	 if (!empty($existe)) { //si existe un monto en este mes
    		$s_a = $existe->inicio_mensual; //saldo actual de mes <
    		$listar = Cuentabienestar::listar($anio, $mes);
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
			//guardar solo en el mes que corresponde (mes - 1)
			DB::table('cbe_cierre_mensual')->where([
    			'activo' => 'S',
    			'anio_id' => $anio,
    			'mes_id' => $mes,
    		])->update(['cierre_mensual' => $ultimo_valor]);
			return $ultimo_valor;
    	}
    	
    }


    public function traer_monto_inicial($anio, $mes)
    {
    	return CierreMensualBienEstar::inicio_mensual($anio, $mes);
    }

    public function listar_cierre_mensual_cbe($anio)
    {
    	// '1' => id que trabaja en ambito sindical
    

    	return CierreMensualBienEstar::listar_cierre_mensual_cbe($anio,'2');
    }
}
