<?php

namespace App\Http\Controllers;

use App\Cuentasindicato;
use App\Montocierrecamping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CsDetalleCampingController extends Controller
{
    public function listar_detalle_camping($anio, $mes, $monto = 0)
    {
    	$monto = $this->traer_cierre_monto_detalle_camping_cs($anio, $mes);
    	$total = 0;

    	$listar = Cuentasindicato::select([
    			DB::raw("concat(cuenta_sindicato.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    			
    			'cuenta_sindicato.descripcion',
    			'monto_ingreso',
    			'monto_egreso',
    			'numero_documento',
    			'definicion'
    	])
    	->join('anio as a','a.id','anio_id')
    	->join('mes as m','m.id','mes_id')
    	->where([
    			'anio_id' => $anio,
    			'mes_id' => $mes,
    			'cuenta_sindicato.activo' => 'S',
    			'tipo_cuenta_sindicato' => '5' //comite camping
    	])->get();

    	if ($listar) {
    		//return $listar;
    		

    		$tomar = true;
            $ingreso = 0;
            $egreso = 0;

			for ($i=0; $i < count($listar); $i++) { 
						//dump('lol: '.$listar[$i]->definicion);
						switch ($listar[$i]->definicion) {
							case '1':  
								if ($tomar == true) {
									//dd($monto);
									$listar[$i]->saldo_actual_raw = $monto + $listar[$i]->monto_ingreso;
									$tomar = false;
									//var_dump("a");

								}else{
									$listar[$i]->saldo_actual_raw = $listar[$i-1]->saldo_actual_raw  + $listar[$i]->monto_ingreso;
									//var_dump("b");
								}

							break;
							case '2':  
								if ($tomar == true) {
									$listar[$i]->saldo_actual_raw = $monto - $listar[$i]->monto_egreso;
									$tomar = false;
									//var_dump("c");
									$ultimo_valor = $listar[$i]->saldo_actual_raw;

								}else{
									
									$listar[$i]->saldo_actual_raw = $listar[$i-1]->saldo_actual_raw - $listar[$i]->monto_egreso;
									//var_dump("d");

								}
							break;
						}

                $ingreso = $ingreso + $listar[$i]->monto_ingreso;   
                $egreso = $egreso + $listar[$i]->monto_egreso; 
                $total = $listar[$i]->saldo_actual_raw ;   

			}

			foreach ($listar as $key) {
    			$key['monto_ingreso'] = number_format($key->monto_ingreso,0,'.',',');
    			$key['monto_egreso'] = number_format($key->monto_egreso,0,'.',',');
    			$key['saldo_actual_raw'] = number_format($key->saldo_actual_raw,0,'.',',');

    		}
    		return [
              'tabla' => $listar,
              'resumen' => [
                    'ingreso' => number_format($ingreso,0,'.',','),
                    'egreso' => number_format($egreso,0,'.',','),
                    'total' => number_format($total,0,'.',',')
              ]
            ];
    	}
    }

    public function guardar_cierre_monto_detalle_camping_cs(Request $r)
    {
    	$exist = Montocierrecamping::where([
    		'anio_id' => $r->anio,
    		'mes_id' => $r->mes,
    		'activo' => 'S'
    	])->first();

    	//dd(empty($exist));
    	if(!empty($exist)){
    		$exist->cierre_calculable = $r->monto;
    		if ($exist->save()) {
    			return ['estado' => 'success','mensaje'=>'Monto actualizado'];
    		}
    		return ['estado' => 'failed','mensaje'=>'Error al actualizar'];
    	}else{
    		$mc = new Montocierrecamping;
	    	$mc->anio_id = $r->anio;
	    	$mc->mes_id = $r->mes;
	    	$mc->cierre_calculable = $r->monto;
	    	$mc->activo = 'S';
	    	if ($mc->save()) {
	    		return ['estado' => 'success','mensaje'=>'Monto añadido'];
	    	}
	    	return ['estado' => 'failed','mensaje'=>'Error al insertar'];
    	}

    }

    public function traer_cierre_monto_detalle_camping_cs($anio, $mes)
    {
    	$get = Montocierrecamping::where([
    		'anio_id' => $anio,
    		'mes_id' => $mes,
    		'activo' => 'S'
    	])->first();

    	if (!empty($get)) {
    		return $get->cierre_calculable;
    	}
    	return 0;
    }
}
