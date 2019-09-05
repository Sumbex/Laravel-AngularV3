<?php

namespace App\Http\Controllers;

use App\Cuentasindicato;
use App\Montocierrecamping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CsDetalleCampingController extends Controller
{
    public function listar_detalle_camping($anio, $mes, $monto = 0)
    {
    	$monto = $this->traer_cierre_monto_detalle_camping_cs($anio, $mes);
    	$total = 0;

    	$listar = Cuentasindicato::select([
                'cuenta_sindicato.id as camping_id',
    			DB::raw("concat(cuenta_sindicato.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    			'archivo_documento',
    			DB::raw("UPPER(cuenta_sindicato.descripcion) as descripcion"),
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
    			'tipo_cuenta_sindicato' => '5', //comite camping
                'detalle_camping' => 'S'
    	])->orderBy('dia','desc')->get();

    	if ($listar) {
    		//return $listar;
    		
            $estado = 'failed';
    		$tomar = true;
            $ingreso = 0;
            $egreso = 0;
            $total = 0;

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
                $estado = "success";

                $this->reajustar_camping_a_cs($ingreso, $egreso, $anio, $mes);

			}
           // dd($listar);

			foreach ($listar as $key) {
    			$key['monto_ingreso'] = $key->monto_ingreso != 0? number_format($key->monto_ingreso,0,'.',','):null;
    			$key['monto_egreso'] = $key->monto_egreso!=0? number_format($key->monto_egreso,0,'.',','):null;
    			$key['saldo_actual_raw'] = number_format($key->saldo_actual_raw,0,'.',',');

    		}
    		return [
              'estado' => $estado,
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

    public function reajustar_camping_a_cs($ingreso, $egreso, $anio, $mes)
    {
        $query_ingreso = Cuentasindicato::where([
                                                'mes_id' => $mes,
                                                'anio_id' => $anio,
                                                'activo' => 'S',
                                                'definicion' =>'1',
                                                'detalle_camping' =>null,
                                                'tipo_cuenta_sindicato'=>'5'
                                            ])->first();

        $query_egreso = Cuentasindicato::where([
                                                'mes_id' => $mes,
                                                'anio_id' => $anio,
                                                'activo' => 'S',
                                                'definicion' =>'2',
                                                'detalle_camping' =>null,
                                                'tipo_cuenta_sindicato'=>'5'
                                            ])->first();

        if (!empty($query_ingreso)) {
            $query_ingreso->monto_ingreso = $ingreso;
            $query_ingreso->save();

        }else{

            $camp_i = new Cuentasindicato;
            $camp_i->dia = '1';
            $camp_i->mes_id = $mes;
            $camp_i->anio_id= $anio;
            $camp_i->numero_documento = '--';
            $camp_i->archivo_documento = '--';
            $camp_i->tipo_cuenta_sindicato = '5';
            $camp_i->descripcion = 'Detalle camping ingreso';
            $camp_i->monto_ingreso = $ingreso;
            $camp_i->definicion = '1';
            $camp_i->user_crea = Auth::user()->id;
            $camp_i->activo = 'S';

            $camp_i->save();
        }

        if (!empty($query_egreso)) {
            $query_egreso->monto_egreso = $egreso;
            $query_egreso->save();

        }else{

            $camp_e = new Cuentasindicato;
            $camp_e->dia = '1';
            $camp_e->mes_id = $mes;
            $camp_e->anio_id= $anio;
            $camp_e->numero_documento = '--';
            $camp_e->archivo_documento = '--';
            $camp_e->tipo_cuenta_sindicato = '5';
            $camp_e->descripcion = 'Detalle camping egreso';
            $camp_e->monto_egreso = $egreso;
            $camp_e->definicion = '2';
            $camp_e->user_crea = Auth::user()->id;
            $camp_e->activo = 'S';

                                                    $camp_e->save();
        }
    }
}
