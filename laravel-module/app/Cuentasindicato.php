<?php

namespace App;

use App\Cuentasindicato;
use App\Consorciopagodiasueldo;
use App\Detalleinteresprestamo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Cuentasindicato extends Model
{
    protected $table = "cuenta_sindicato";


    protected function traer($anio, $mes)
    {
    	$get_anio = DB::table('mes as m')->select('m.descripcion')->where('id',$mes)->first();

    	// $interes = Detalleinteresprestamo::item_interes_a_cs($anio, $mes);
		$prestamo_egreso = $this->item_prestamo_e_cs($anio, $mes);
		$consorcio_egreso = Consorciopagodiasueldo::item_cpds($anio, $mes);

		
    	//$prestamo_ingreso = $this->item_prestamo_i_cs($anio, $mes); este se comenta por peticion del tio emilio


		// if($interes != null){ // si existe interes de prestamo
		// 	$verify_interes = $this->where([
		// 		'interes' => 'S', // si es un automatico de interes
		// 		'activo' => 'S',
		// 		'tipo_cuenta_sindicato' => '1',//cuenta tipo fijo
		// 		'anio_id' => $anio,
		// 		'mes_id' => $mes
		// 	])->first();

			

		// 	if(empty($verify_interes)){

		// 		$this->numero_documento = '--';
		// 		$this->archivo_documento = '--';
		// 		$this->tipo_cuenta_sindicato = 1; //fijo
		// 		$this->descripcion = $interes['descripcion'];
		// 		$this->monto_ingreso = $interes['monto_ingreso'];
		// 		$this->monto_egreso = null;
		// 		$this->saldo_actual = null;
		// 		$this->definicion = 1;
		// 		$this->user_crea = Auth::user()->id;
		// 		$this->activo = 'S';
		// 		$this->anio_id = $anio;
		// 		$this->mes_id = $mes;
		// 		$this->dia = '1';
		// 		$this->interes = 'S';
		// 		$this->save();

		// 	}else{

		// 		$verify_interes->monto_ingreso = $interes['monto_ingreso'];
		// 		$verify_interes->save();
				
		// 	}
		// }
			$i = new Cuentasindicato;
			$e = new Cuentasindicato;

			// $verify_p_ingreso = $this->where([
			// 	'p_i' => 'S',
			// 	'activo' => 'S',
			// 	'tipo_cuenta_sindicato' => '4',//cuenta prestamos
			// 	'anio_id' => $anio,
			// 	'mes_id' => $mes,
			// 	'definicion' => '1'
			// ])->first();


			// if (empty($verify_p_ingreso)) {
			// 	$i->numero_documento = '---';
			// 	$i->archivo_documento = '---';
			// 	$i->tipo_cuenta_sindicato = 4; //prestamo
			// 	$i->descripcion = 'Total ingreso de prestamos, mes de '.$get_anio->descripcion;
			// 	$i->monto_ingreso = $prestamo_ingreso;
			// 	$i->monto_egreso = null;
			// 	$i->saldo_actual = null;
			// 	$i->definicion = 1;
			// 	$i->user_crea = Auth::user()->id;
			// 	$i->activo = 'S';
			// 	$i->anio_id = $anio;
			// 	$i->mes_id = $mes;
			// 	$i->dia = '1';
			// 	$i->p_i = 'S';
			// 	$i->save();
			// }else{
			// 	$verify_p_ingreso->monto_ingreso = $prestamo_ingreso;
			// 	$verify_p_ingreso->save();
			// }


			$verify_p_egreso = $this->where([
				'p_e' => 'S',
				'activo' => 'S',
				'tipo_cuenta_sindicato' => '4',//cuenta prestamos
				'anio_id' => $anio,
				'mes_id' => $mes,
				'definicion' => '2'
			])->first();

			if (empty($verify_p_egreso)) {
				$e->numero_documento = '--';
				$e->archivo_documento = '--';
				$e->tipo_cuenta_sindicato = 4; //prestamo
				$e->descripcion = 'Total egreso de prestamos, mes de '.$get_anio->descripcion;
				$e->monto_ingreso = null;
				$e->monto_egreso = $prestamo_egreso;
				$e->saldo_actual = null;
				$e->definicion = 2;
				$e->user_crea = Auth::user()->id;
				$e->activo = 'S';
				$e->anio_id = $anio;
				$e->mes_id = $mes;
				$e->dia = '1';
				$e->p_e = 'S';
				$e->save();
			}else{
				$verify_p_egreso->monto_egreso = $prestamo_egreso;
				$verify_p_egreso->save();
			}
		

    	$listar = $this::select([
    						'cuenta_sindicato.id',
    						DB::raw("concat(cuenta_sindicato.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    						'cuenta_sindicato.numero_documento',
    						'cuenta_sindicato.archivo_documento',
    						'cuenta_sindicato.tipo_cuenta_sindicato',
    						DB::raw("UPPER(cuenta_sindicato.descripcion) as descripcion"),
    						'cuenta_sindicato.monto_ingreso',
    						'cuenta_sindicato.monto_egreso',
    						'cuenta_sindicato.definicion'
    					])
    				   ->join('anio as a','a.id','anio_id')
    				   ->join('mes as m','m.id','mes_id')
    				   ->where('cuenta_sindicato.detalle_camping', null)
				       ->where([
							'cuenta_sindicato.activo'=>'S',
							'cuenta_sindicato.anio_id' => $anio, 
							'cuenta_sindicato.mes_id' => $mes,
							])->orderBy('tipo_cuenta_sindicato','asc')
				       		  ->orderBy('cuenta_sindicato.dia','asc')
				       		  ->get();

	    

	    


			       		  


		return ($listar);
    }

    protected function resultado_cuenta_sindical($anio, $mes)
	{
			$ingreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			->where('cuenta_sindicato.detalle_camping', null)
			->sum('monto_ingreso');
			$egreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			->where('cuenta_sindicato.detalle_camping', null)
			->sum('monto_egreso');
			$total = $ingreso_total - $egreso_total;

			$anterior = DB::table('c_s_cierre_mensual')->select('inicio_mensual')->where([
				'mes_id'=> $mes, 'anio_id' => $anio
			])->first();

			return [
				'ingreso_total' => $ingreso_total,
				'egreso_total' => $egreso_total,
				'total_actual' => $total,
				'total_resumen' => $total.' + '. $anterior->inicio_mensual.' = '.($total + $anterior->inicio_mensual),
				'total_final' => ($total + $anterior->inicio_mensual)
			];

	}

	public function item_prestamos_a_cs($anio, $mes)
	{
		$dp_query = DB::select("SELECT 
								--prestamo_id,
							    COALESCE(sum(monto_egreso),0) as monto_egreso,
							    COALESCE(sum(monto_ingreso),0)as monto_ingreso
							from detalle_prestamo where mes_id = $mes and anio_id = $anio and activo = 'S'");

		$dpta_query = DB::select("SELECT 
								--prestamo_id,
							    COALESCE(sum(monto_egreso),0) as monto_egreso,
							    COALESCE(sum(monto_ingreso),0)as monto_ingreso
							from detalle_prestamo_tipo_abono where mes_id = $mes and anio_id = $anio and activo = 'S'");

		// dd($dp_query[0]->monto_egreso);


		if ($dp_query > 0 && $dpta_query > 0) {

			return ([
				'ingreso' => $dp_query[0]->monto_ingreso + $dpta_query[0]->monto_ingreso,
				'egreso' => $dp_query[0]->monto_egreso + $dpta_query[0]->monto_egreso
				
			]);

		}else{
			return null;
		}

		
	}

	public function item_prestamo_e_cs($anio, $mes)
	{

		$salud_retor = DB::select("SELECT COALESCE(sum(egreso),0)as egreso  from p_salud_retornable
									where mes_id = $mes and anio_id = $anio and definicion = 2");

		$apuro = DB::select("SELECT COALESCE(sum(egreso),0)as egreso  from p_apuro_economico_retornable
									where mes_id = $mes and anio_id = $anio and definicion = 2 and activo = 'S'");

		$aporte = DB::select("SELECT COALESCE(sum(egreso),0) as egreso from cs_prestamo
									where mes_id = $mes and anio_id = $anio and definicion = 2 and activo ='S' 
									and estado_prestamo ='egresado'");

		return $salud_retor[0]->egreso + $apuro[0]->egreso + $aporte[0]->egreso;
		
		

		
	}

	public function item_prestamo_i_cs($anio, $mes)
	{
		

		// $dp_query = DB::select("SELECT 
      
		// 						COALESCE(sum(z.calculo),0) as suma from 

		// 						(select 
		// 						    (monto_ingreso) as calculo
		// 						from detalle_prestamo where definicion=1 and mes_id=$mes and anio_id=$anio) z ");

		// $dpta_query = DB::select("SELECT COALESCE(sum(monto_pagado),0) as monto_egreso
		// 			from detalle_prestamo_tipo_abono where definicion = 1 
		// 			and mes_id = $mes and anio_id = $anio and activo = 'S'");	
		$salud_retor = DB::select("SELECT COALESCE(sum(ingreso),0)as ingreso  from p_salud_retornable
									where mes_id = $mes and anio_id = $anio and definicion = 1");

		$apuro = DB::select("SELECT COALESCE(sum(ingreso),0)as ingreso  from p_apuro_economico_retornable
									where mes_id = $mes and anio_id = $anio and definicion = 1");

		$abonos = DB::select("SELECT COALESCE(sum(monto),0) as monto from abonos_salud_retornable where mes_id = $mes and anio_id = $anio");

		return $salud_retor[0]->ingreso + $apuro[0]->ingreso + $abonos[0]->monto;


		
		
	}



	// $dpta_query = DB::select("SELECT COALESCE(sum(monto_ingreso),0) as monto_ingreso
		// from detalle_prestamo_tipo_abono where definicion = 1 and mes_id = $mes and anio_id = $anio and activo = 'S'");
}
