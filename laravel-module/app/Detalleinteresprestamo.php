<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Detalleinteresprestamo extends Model
{
    protected $table = "detalle_interes_prestamo";

    protected function traer_lista($anio, $mes)
    {	
    	
    	$total =0;
    	$list = $this->select([
    				'p.id as prestamo_id',
    				'ip.interes as interes_total',
    				'detalle_interes_prestamo.interes_mensual',
    				 DB::raw("concat(detalle_interes_prestamo.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    				 'p.descripcion'
    			])
    			->join('interes_prestamo as ip','ip.id','detalle_interes_prestamo.interes_prestamo_id')
    			->join('cs_prestamos as p','ip.prestamo_id','p.id')
    			->join('anio as a', 'a.id', 'detalle_interes_prestamo.anio_id')
          		->join('mes as m', 'm.id', 'detalle_interes_prestamo.mes_id')
          		->where([
          			'detalle_interes_prestamo.anio_id' => $anio,
          			'detalle_interes_prestamo.mes_id' => $mes
          		])
    			->get();
    

    	foreach ($list as $key) {
    		$total = $total + (int)$key->interes_mensual;
    		$key['interes_total'] = number_format($key->interes_total,0,'.',',');
    		$key['interes_mensual'] = number_format($key->interes_mensual,0,'.',',');

    	}

    	return [
    		'tabla'=>$list, 
    		'total'=> number_format($total,0,'.',',')
    	];
    }

    // protected function item_interes_a_cs($anio, $mes)//este item esta listo para irse a la cuenta sindical cuando se llame este metodo
    // {	
    // 	$get_anio = DB::table('mes as m')->select('m.descripcion')->where('id',$mes)->first();


    // 	// $list = $this->join('interes_prestamo as ip','ip.id','detalle_interes_prestamo.interes_prestamo_id')
    // 	// 		->join('cs_prestamos as p','ip.prestamo_id','p.id')
    // 	// 		->join('anio as a', 'a.id', 'detalle_interes_prestamo.anio_id')
    //  //      		->join('mes as m', 'm.id', 'detalle_interes_prestamo.mes_id')
    //  //      		->where([
    //  //      			'detalle_interes_prestamo.anio_id' => $anio,
    //  //      			'detalle_interes_prestamo.mes_id' => $mes
    //  //      		])
    // 	// 		->sum('detalle_interes_prestamo.interes_mensual');

    //     $list = DB::select("SELECT COALESCE(sum(interes_mensual),0) as interes_mensual  from p_apuro_economico_retornable
    //                                 where mes_id = $mes and anio_id = $anio and definicion = 1 and activo = 'S';");

    	

	//     	return [
	//     		'id' => '',
	//     		'numero_documento' => '',
	//     		'fecha' => '',
	//     		'archivo_documento' => '',
	//     		'tipo_cuenta_sindicato' => 1,
	//     		'descripcion' =>  'Aporte de prestamo de apuro económico del mes de '.$get_anio->descripcion,
	//     		'monto_ingreso' =>	(int)$list[0]->interes_mensual,
	//     		'monto_egreso' => '',
	//     		'definicion' => 1,
	//     		'saldo_actual_raw' =>0
	//     	];
    	
    // 	// 'cuenta_sindicato.id',
    // 	// 					DB::raw("concat(cuenta_sindicato.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    // 	// 					'cuenta_sindicato.numero_documento',
    // 	// 					'cuenta_sindicato.archivo_documento',
    // 	// 					'cuenta_sindicato.tipo_cuenta_sindicato',
    // 	// 					'cuenta_sindicato.descripcion',
    // 	// 					'cuenta_sindicato.monto_ingreso',
    // 	// 					'cuenta_sindicato.monto_egreso',
    // 	// 					'cuenta_sindicato.definicion'
	// }
	protected function item_interes_a_be($anio, $mes)//este item esta listo para irse a la cuenta bienestar cuando se llame este metodo
    {	

		$get_mes = DB::table('mes as m')->select(['m.descripcion','m.id'])->where('id',$mes)->first();
		$get_anio = DB::table('anio as a')->select('a.descripcion')->where('id',$anio)->first();
		
         
		if ($get_anio->descripcion == 2020) {

			// dd($get_mes->id);
			if ($get_mes->id >= 8 ) {
				
				$list = DB::select("SELECT COALESCE(sum(interes_mensual),0) as interes_mensual  from p_apuro_economico_retornable
								where mes_id = $mes and anio_id = $anio and definicion = 1 and activo = 'S';");

				return [
					'id' => '',
					'numero_documento' => '',
					'fecha' => '',
					'archivo_documento' => '',
					'tipo_cuenta_sindicato' => 1,
					'descripcion' =>  'Aporte de prestamo de apuro económico del mes de '.$get_mes->descripcion,
					'monto_ingreso' =>	(int)$list[0]->interes_mensual,
					'monto_egreso' => '',
					'definicion' => 1,
					'saldo_actual_raw' =>0
				];
			}
	    
		}

		if ($get_anio->descripcion > 2020) {

			if ($get_mes->descripcion > 0 ) {
				$list = DB::select("SELECT COALESCE(sum(interes_mensual),0) as interes_mensual  from p_apuro_economico_retornable
								where mes_id = $mes and anio_id = $anio and definicion = 1 and activo = 'S';");

				return [
					'id' => '',
					'numero_documento' => '',
					'fecha' => '',
					'archivo_documento' => '',
					'tipo_cuenta_sindicato' => 1,
					'descripcion' =>  'Aporte de prestamo de apuro económico del mes de '.$get_mes->descripcion,
					'monto_ingreso' =>	(int)$list[0]->interes_mensual,
					'monto_egreso' => '',
					'definicion' => 1,
					'saldo_actual_raw' =>0
				];
			}
		}

		return null;

				
    	
    	
    }
}
