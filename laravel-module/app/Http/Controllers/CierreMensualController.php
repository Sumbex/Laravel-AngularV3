<?php

namespace App\Http\Controllers;

use App\Cuentasindicato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CierreMensualController extends Controller
{
    public function calcular_cierre_e_inicio_mensual($anio, $mes)
    {
    	$mes = $mes - 1;

    	$existe = DB::table('c_s_cierre_mensual')->where([
    		'activo' => 'S',
    		'anio_id' => $anio,
    		'mes_id' => $mes,
    	])->first();


    	 //dd($existe);

    	 if (!empty($existe)) { //si existe un monto en este mes
    		$s_a = $existe->inicio_mensual;
    		$listar = Cuentasindicato::where(['anio_id' => $anio, 'mes_id' => $mes])->get();

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
			DB::table('c_s_cierre_mensual')->where([
    			'activo' => 'S',
    			'anio_id' => $anio,
    			'mes_id' => $mes,
    		])->update(['cierre_mensual' => $ultimo_valor]);

			return $ultimo_valor;


    	}

    	
    }
}
