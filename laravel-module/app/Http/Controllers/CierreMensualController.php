<?php
namespace App\Http\Controllers;
use App\CierreMensualSindical;
use App\Cuentasindicato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CierreMensualController extends Controller
{   //cierre mensual referencia a cuenta sindical
	public function guardar_inicio_mensual(Request $r)
    {
    	$existe = DB::table('c_s_cierre_mensual')->where([
    		'activo' => 'S',
    		'anio_id' => $r->anio,
    		'mes_id' => $r->mes,
    	])->first();


    	if (!empty($existe)) { //si existe un monto en este mes
    		$update = DB::table('c_s_cierre_mensual')->where([
    		'activo' => 'S',
    		'anio_id' => $r->anio,
    		'mes_id' => $r->mes,
    		])->update(['inicio_mensual'=>$r->cierre_mensual]);
    		return response()->json($update);

    	}else{
    		$insert = DB::table('c_s_cierre_mensual')->insert([
	    		'anio_id' => $r->anio,
	    		'mes_id' => $r->mes,
	    		'inicio_mensual' => $r->cierre_mensual,
	    		'activo' => 'S'
	        ]);

	        return response()->json($insert);
    	}
	
    }
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
    		$s_a = $existe->inicio_mensual; //saldo actual de mes <
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

    public function listar_cierre_mensual_cs($anio, $mes)
    {
    	// '1' => id que trabaja en ambito sindical

    	return CierreMensualSindical::listar_cierre_mensual_cs($anio, $mes,'1');
    }

    //cierre mensual referencia a cuenta sindical
}