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
    	$interes = Detalleinteresprestamo::item_interes_a_cs($anio, $mes);

     	//dd($interes['monto_ingreso']);
  //   	if($interes != null){ // si existe interes de prestamo
		// 	$get_interes = $interes;
		// 	$listar->push(collect($get_interes));// se agrega obeto a consulta (el item genberal de los intereses)

	
		// }
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
}
