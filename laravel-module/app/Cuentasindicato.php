<?php

namespace App;

use App\Detalleinteresprestamo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cuentasindicato extends Model
{
    protected $table = "cuenta_sindicato";


    protected function traer($anio, $mes)
    {
    	$get_anio = DB::table('mes as m')->select('m.descripcion')->where('id',$mes)->first();

    	$interes = Detalleinteresprestamo::item_interes_a_cs($anio, $mes);
    	//$prestamo = $this->item_prestamos_a_cs($anio, $mes);

    	$prestamo_egreso = $this->item_prestamo_e_cs($anio, $mes);
    	$prestamo_ingreso = $this->item_prestamo_i_cs($anio, $mes);

    	//dd($prestamo_ingreso);



		if($interes != null){ // si existe interes de prestamo
			$verify_interes = $this->where([
				'interes' => 'S',
				'activo' => 'S',
				'tipo_cuenta_sindicato' => '1',//cuenta tipo fijo
				'anio_id' => $anio,
				'mes_id' => $mes
			])->first();

			if(empty($verify_interes)){

				$this->numero_documento = '--';
				$this->archivo_documento = '--';
				$this->tipo_cuenta_sindicato = 1; //fijo
				$this->descripcion = $interes['descripcion'];
				$this->monto_ingreso = $interes['monto_ingreso'];
				$this->monto_egreso = null;
				$this->saldo_actual = null;
				$this->definicion = 1;
				$this->user_crea = Auth::user()->id;
				$this->activo = 'S';
				$this->anio_id = $anio;
				$this->mes_id = $mes;
				$this->dia = '1';
				$this->interes = 'S';
				$this->save();

			}else{

				$verify_interes->monto_ingreso = $interes['monto_ingreso'];
				$verify_interes->save();
				
			}
		}

			$verify_p_ingreso = $this->where([
				'p_i' => 'S',
				'activo' => 'S',
				'tipo_cuenta_sindicato' => '4',//cuenta prestamos
				'anio_id' => $anio,
				'mes_id' => $mes,
				'definicion' => '1'
			])->first();


			if (empty($verify_p_ingreso)) {
				$this->numero_documento = '---';
				$this->archivo_documento = '---';
				$this->tipo_cuenta_sindicato = 4; //prestamo
				$this->descripcion = 'Total ingreso de prestamos, mes de '.$get_anio->descripcion;
				$this->monto_ingreso = $prestamo_ingreso;
				$this->monto_egreso = null;
				$this->saldo_actual = null;
				$this->definicion = 1;
				$this->user_crea = Auth::user()->id;
				$this->activo = 'S';
				$this->anio_id = $anio;
				$this->mes_id = $mes;
				$this->dia = '1';
				$this->p_i = 'S';
				$this->save();
			}else{
				$verify_p_ingreso->monto_ingreso = $prestamo_ingreso;
				$verify_p_ingreso->save();
			}


			$verify_p_egreso = $this->where([
				'p_e' => 'S',
				'activo' => 'S',
				'tipo_cuenta_sindicato' => '4',//cuenta prestamos
				'anio_id' => $anio,
				'mes_id' => $mes,
				'definicion' => '2'
			])->first();

			if (empty($verify_p_egreso)) {
				$this->numero_documento = '--';
				$this->archivo_documento = '--';
				$this->tipo_cuenta_sindicato = 4; //prestamo
				$this->descripcion = 'Total egreso de prestamos, mes de '.$get_anio->descripcion;
				$this->monto_ingreso = null;
				$this->monto_egreso = $prestamo_egreso;
				$this->saldo_actual = null;
				$this->definicion = 2;
				$this->user_crea = Auth::user()->id;
				$this->activo = 'S';
				$this->anio_id = $anio;
				$this->mes_id = $mes;
				$this->dia = '1';
				$this->p_e = 'S';
				$this->save();
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
    						'cuenta_sindicato.descripcion',
    						'cuenta_sindicato.monto_ingreso',
    						'cuenta_sindicato.monto_egreso',
    						'cuenta_sindicato.definicion'
    					])
    				   ->join('anio as a','a.id','anio_id')
    				   ->join('mes as m','m.id','mes_id')
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
			->sum('monto_ingreso');
			$egreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
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

		$dp_query = DB::select("SELECT COALESCE(sum(monto_egreso),0) as monto_egreso 
 								from cs_prestamos where mes_id = $mes and anio_id = $anio and activo='S'");


		
		if ($dp_query[0]->monto_egreso > 0) {

			return $dp_query[0]->monto_egreso;

		}else{
			return 0;
		}

		
	}

	public function item_prestamo_i_cs($anio, $mes)
	{
		
		$dp_query = DB::select("SELECT 
      
								COALESCE(sum(z.calculo),0) as suma from 

								(select 
								    (monto_ingreso / cuota) as calculo
								from detalle_prestamo where definicion=1 and mes_id=$mes and anio_id=$anio) z ");

		$dpta_query = DB::select("SELECT COALESCE(sum(monto_pagado),0) as monto_egreso
					from detalle_prestamo_tipo_abono where definicion = 1 
					and mes_id = $mes and anio_id = $anio and activo = 'S'");


		if ($dp_query[0]->suma > 0 ) {

			$suma = (int)$dp_query[0]->suma + (int)$dpta_query[0]->monto_egreso;
			return $suma;

		}else{
			return 0;
		}
		
	}



	// $dpta_query = DB::select("SELECT COALESCE(sum(monto_ingreso),0) as monto_ingreso
		// from detalle_prestamo_tipo_abono where definicion = 1 and mes_id = $mes and anio_id = $anio and activo = 'S'");
}
