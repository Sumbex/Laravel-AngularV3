<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
