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

    protected function item_interes_a_cs($anio, $mes)//este item esta listo para irse a la cuenta sindical cuando se llame este metodo
    {	
    	$get_anio = DB::table('mes as m')->select('m.descripcion')->where('id',$mes)->first();


    	$list = $this->join('interes_prestamo as ip','ip.id','detalle_interes_prestamo.interes_prestamo_id')
    			->join('cs_prestamos as p','ip.prestamo_id','p.id')
    			->join('anio as a', 'a.id', 'detalle_interes_prestamo.anio_id')
          		->join('mes as m', 'm.id', 'detalle_interes_prestamo.mes_id')
          		->where([
          			'detalle_interes_prestamo.anio_id' => $anio,
          			'detalle_interes_prestamo.mes_id' => $mes
          		])
    			->sum('detalle_interes_prestamo.interes_mensual');

    	if($list > 0){

	    	return [
	    		'id' => '',
	    		'numero_documento' => '',
	    		'fecha' => '',
	    		'archivo_documento' => '',
	    		'tipo_cuenta_sindicato' => 1,
	    		'descripcion' =>  'Interes prestamo apuro del mes de '.$get_anio->descripcion,
	    		'monto_ingreso' =>	(int)$list,
	    		'monto_egreso' => '',
	    		'definicion' => 1,
	    		'saldo_actual_raw' =>0
	    	];
    	}
    	return null;
    	// 'cuenta_sindicato.id',
    	// 					DB::raw("concat(cuenta_sindicato.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    	// 					'cuenta_sindicato.numero_documento',
    	// 					'cuenta_sindicato.archivo_documento',
    	// 					'cuenta_sindicato.tipo_cuenta_sindicato',
    	// 					'cuenta_sindicato.descripcion',
    	// 					'cuenta_sindicato.monto_ingreso',
    	// 					'cuenta_sindicato.monto_egreso',
    	// 					'cuenta_sindicato.definicion'
    }
}
