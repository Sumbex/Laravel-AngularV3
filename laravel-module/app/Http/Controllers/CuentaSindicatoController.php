<?php

namespace App\Http\Controllers;

use App\Cuentasindicato;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CuentaSindicatoController extends Controller
{
	public $global_caja_chica = 100000;

	public function validar_datos_cs($request)
	{
		 $validator = Validator::make($request->all(), 
		 	[
	            'fecha' => 'required',
	            'n_documento' => 'required|unique:cuenta_sindicato,numero_documento',
	            'descripcion' => 'required|min:0',
	            'definicion' => 'required|min:0',
	            'tipo_cuenta_sindicato' => 'required',
	            'monto' => 'required',
	            'archivo' => 'required|mimes:doc,pdf,docx',
	        ],
	        [
	        	'fecha.required' => 'La fecha es necesaria',
	        	'n_documento.required' => 'El numero de documento es necesario',
	        	'n_documento.unique' => 'El numero de documento ya existe en tus registros',
	        	'descripcion.required' => 'La descripcion es necesaria',
	        	'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
	        	'tipo_cuenta_sindicato.required' => 'Especifique el tipo de cuenta de su detalle',
	        	'monto.required' => 'El monto es necesario'
	        ]);

 
	        if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
	}
	public function div_fecha($value)//funciona con input type date 
    {
    	$fecha = $value;
		$ano = substr($fecha, -10, 4);
		$mes = substr($fecha, -5, 2);
		$dia = substr($fecha, -2, 2);
		return [
			'anio' => $ano, 
			'mes'  => $mes, 
			'dia'  => $dia
		];
    }
    public function anio_tipo_id($value)
    {

    	$data = DB::table('anio')->where('descripcion', $value)->first();

    	if(!empty($data)){

    	 	return $data;
    	} 
    		
    }
    // guardando aqui desde el formulario de html
    public function guardar_item_cuenta_sindicato(Request $r)
	{

		try{

			$file = $this->guardarArchivo($r->archivo,'arvhivos_sindical/');

			if($file['estado'] == "success"){
				$archivo = $file['archivo'];
			}else{
				return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
			}


			$f = $this->div_fecha($r->fecha);
			
			$anio = $this->anio_tipo_id($f['anio']);

			//si existe caja chica, no volver a ingresar
			if($this->existe_item_caja_chica($anio->id, $f['mes'], $r->tipo_cuenta_sindicato))
			{
			return [ 'estado' =>'failed', 'mensaje'=>'Ya existe item caja chica en este mes' ];

			}else{

				//$caja_ch_monto_anterior = $this->existe_dinero_mes_anterior_caja_chica($f['anio'], $f['mes']);

				//dd($caja_ch_monto_anterior['monto']);

				$validacion = $this->validar_datos_cs($r);

				
				if($validacion['estado'] == 'success'){

					$c_m_txt = 'c_s_cierre_mensual';

					$anio = $this->anio_tipo_id($f['anio']);

						//verifico si existe un monto inicial en este mes
					$exis_monto_init =  DB::table($c_m_txt)->where([
											'activo'  => 'S',
											'anio_id' => $anio->id/*$f['anio']*/,
											'mes_id'  => $f['mes'],
						    			])->first();
							
					// si no existe, primero calcular o insertar manual el monto inicial(toma de mes anterior)
					if(empty($exis_monto_init)){

						return [
							"estado"  => "failed", 
							"mensaje" => "No existe monto inicial, primero calcule"
						];

					}
					else {

						$saldo_actual = Cuentasindicato::where([
							'activo'  => 'S',
							'anio_id' => $anio->id /*$f['anio']*/,
							'mes_id'  => $f['mes']
						])->orderby('created_at','DESC')->take(1)->get();

								
								
						$c_m = DB::table($c_m_txt)->where([
							'activo'  => 'S',
							'anio_id' => $anio->id,
							'mes_id'  => $f['mes'],
						])->first();
								
								//valido si no hay registros en este mes (items)
						$s_a = (empty($saldo_actual[0]['saldo_actual'])? $c_m->inicio_mensual : $saldo_actual[0]['saldo_actual']);

								
						$cs = new Cuentasindicato;
						$cs->anio_id = $anio->id/*$f['anio']*/;
						$cs->mes_id  = $f['mes'];
						$cs->dia     = $f['dia'];
						$cs->numero_documento  = $r->n_documento;
						$cs->archivo_documento = '/doc/archivo.pdf'; //valor por mientras
						$cs->tipo_cuenta_sindicato = $r->tipo_cuenta_sindicato;
						$cs->descripcion = $r->descripcion;

						$cs->archivo_documento = $archivo; 

						// dd($r->definicion);
						switch ($r->definicion) {// si es ingreso o egreso
							case '1':  
								$cs->monto_ingreso = $r->monto; 
								$cs->saldo_actual  = $s_a + $r->monto;
							break;

							case '2':  
								
								//si el item entrante es un caja
								if ($r->tipo_cuenta_sindicato == 3){
									
									if($r->monto > $this->global_caja_chica ){
									
										return [
										'estado'  => 'failed', 
										'mensaje' => 'el monto ingresado supera los '.number_format($this->global_caja_chica,0,'.',',').' pesos'
										];
									}
									$cch_monto_anterior = $this->existe_dinero_mes_anterior_caja_chica($f['anio'], $f['mes']);
									// dd("$r->monto > ".$cch_monto_anterior['monto'].'= '.$r->monto > $cch_monto_anterior['monto']);
									if($r->monto > $cch_monto_anterior['monto']){
										
											$cs->monto_egreso = $r->monto; 
											$cs->saldo_actual = $s_a - ($r->monto); 								
										//return ['estado' => 'failed', 'mensaje'=>'el monto ingresado ('.$r->monto.')'];
										
									}else{
										$cs->monto_egreso = /*$caja_ch_monto_anterior['monto'] +*/ $r->monto; 
									
										$cs->saldo_actual = $s_a - (/*$caja_ch_monto_anterior['monto'] +*/ $r->monto); 
									}
									
												//var_dump("paso por cuenta 3 con su monto");
								}
								else{
									$cs->monto_egreso = $r->monto; 
									$cs->saldo_actual = $s_a - $r->monto;
								}

							break;
						}

						$cs->activo     = 'S';
						$cs->definicion = $r->definicion;
						$cs->user_crea  = Auth::user()->id;

						if ($cs->save()) {
							return [
								'estado'  => 'success', 
								'mensaje' => "Item de cuenta sindical añadido"
							];
						}
						return [
							'estado'  => 'failed', 
							'mensaje' => 'Algo salió mal, intente nuevamente'];
							}

				}
				else {

					return $this->validar_datos_cs($r);
				}
		

					
			}
		}catch(QueryException $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos'
			];
		}
		catch(\Exception $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos'
			];
		}
		
	}

	public function listar_cuenta_sindicato($anio, $mes)
	{

		try{

			$cm_txt = 'c_s_cierre_mensual';
			
			$c_m = DB::table($cm_txt)->where(['activo' => 'S','anio_id' => $anio,'mes_id' => $mes,
		    	])->first();
			

			if(!empty($c_m->inicio_mensual)){
				$s_a = $c_m->inicio_mensual;

				$data = $this->cuenta_sindical($s_a, $anio, $mes);
			    

				return $data;
			}
		}
		catch(QueryException $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido traer la lista cuenta sindical',
				'exception' => $e //este es solo pal desarrollador;
			];
		}
		catch(\Exception $e){
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido traer la lista cuenta sindical',
				'exception' => $e //este es solo pal desarrollador;
			];
		}
	}

	public function cuenta_sindical($s_a, $anio, $mes)
	{
		$listar = Cuentasindicato::traer($anio, $mes);
		$tomar = true;

			for ($i=0; $i < count($listar); $i++) { 
			
						switch ($listar[$i]->definicion) {
							case '1':  
								if ($tomar == true) {
									$listar[$i]->saldo_actual_raw = $s_a + $listar[$i]->monto_ingreso;
									$tomar = false;

								}else{
									$listar[$i]->saldo_actual_raw = $listar[$i-1]->saldo_actual_raw  + $listar[$i]->monto_ingreso;
								}

							break;
							case '2':  
								if ($tomar == true) {
									$listar[$i]->saldo_actual_raw = $s_a - $listar[$i]->monto_egreso;
									$tomar = false;
									$ultimo_valor = $listar[$i]->saldo_actual_raw;

								}else{
									
									$listar[$i]->saldo_actual_raw = $listar[$i-1]->saldo_actual_raw - $listar[$i]->monto_egreso;

								}
							break;
						}
			}
			$return = [];
			$return['fijo']=[];
			$return['variable']=[];
			$return['caja_chica']=[];
			$return['prestamo']=[];
			$return['camping']=[];

			foreach ($listar as $key) {
				switch ($key->tipo_cuenta_sindicato) {
					case '1': $return['fijo'][] = $key; break;
					case '2': $return['variable'][] = $key; break;
					case '3': $return['caja_chica'][] = $key; break;
					case '4': $return['prestamo'][] = $key; break;
					case '5': $return['camping'][] = $key; break;
					
					default:
						# code...
						break;
				}
			}
			$return['resultado'] = Cuentasindicato::resultado_cuenta_sindical($anio, $mes);

			return $return;
	}

	public function existe_item_caja_chica($anio, $mes, $tipo_cuenta)
	{

		if($tipo_cuenta == 3){

			$existe = Cuentasindicato::where([
				'activo' => 'S', 
				'tipo_cuenta_sindicato' => $tipo_cuenta ,//caja_chica
				'mes_id' => $mes, 'anio_id' => $anio
			])->first();

			//dd(empty($existe));

			if (!empty($existe)) {
				if ($existe->tipo_cuenta_sindicato == 3/*cajachica*/) {
					return true;
				}else{
					return false;
				}
			}
			return false;

			
		}else{
			return false;
		}

		// return !empty($existe) ? $existe : null;
	}


	//este metodo recibe el año como tal
	public function existe_dinero_mes_anterior_caja_chica($anio, $mes)
	{	

		$mes_anterior = $mes - 1;
		$anio_anterior = $anio;

		if ($mes_anterior == 0) { //si la fecha capta el mes anterior (diciembre )tomar el valor del año tambien
			$mes_anterior = 12;
			$anio_anterior = $anio - 1;
			
			try{
				$anio = DB::table('anio')->where(['activo'=>'S', 'descripcion'=> "$anio_anterior" ])->get();
				
				$monto = DB::table('cs_caja_chica')->where([
							'activo' => 'S',
							'anio_id' => $anio->id,
							'mes_id' => $mes_anterior
						])->sum('monto_egreso');

				$monto_a_guardar = $this->global_caja_chica - $monto;
				if ($monto_a_guardar == 100000) {
					return ['estado'=>'success', 'monto'=>0];
				}

				return ['estado'=>'success', 'monto'=>$monto_a_guardar];
				// $monto_sobrante = $this->global_caja_chica - $monto;
				// if (empty($monto)) {
				// 	return ['estado'=>"success",'monto'=>0];
				// }
			}catch (\Exception $e)
		    {

		        return ['estado'=>"failed",'monto'=>0];
		    }

		}
		else
		{
			$anio = DB::table('anio')->where(['activo'=>'S', 'descripcion'=> ($anio_anterior) ])->first();


			//dd($anio->id.'; mes_id:'.$mes_anterior);
			$monto = DB::table('cs_caja_chica')->where([
						'activo' => 'S',
						'anio_id' => $anio->id,
						'mes_id' => $mes_anterior
					])->sum('monto_egreso');



			$monto_a_guardar = $this->global_caja_chica - $monto;
			if ($monto_a_guardar == 100000) {
				return ['estado'=>'success', 'monto'=>0];
			}
			return ['estado'=>'success', 'monto'=>$monto_a_guardar];

			// $monto_sobrante = $this->global_caja_chica - $monto;
			// if (empty($monto)) {
			// 	return ['estado'=>"success",'monto'=>0];
			// }

		}

		// if($monto_sobrante < $this->global_caja_chica ){
		// 	return ['estado'=>'success', 'monto'=>$monto_sobrante];
		// }else{
		// 	return ['estado'=>'failed', 'monto'=> 'monto muy elevado a '.$this->global_caja_chica ];
		// }
		

	}

	// public function guardarArchivo($archivo, $ruta){
        
 //        $filenameext = $archivo->getClientOriginalName();
 //        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
 //        $extension = $archivo->getClientOriginalExtension();
 //        $nombreArchivo = $filename.'_'.time().'.'.$extension;

 //        $guardar = Storage::put($ruta . $nombreArchivo, $filename, 'public');

 //        $archivo = $ruta.$nombreArchivo;
 //        if($guardar){
 //            return [ 'estado'=>'success', 'archivo' => $archivo ];
 //        }else{
 //            return [ 'estado'=>'failed'];
 //        }


 //    }

     protected function guardarArchivo($archivo, $ruta)
    {
    	try{
	        $filenameext = $archivo->getClientOriginalName();
	        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
	        $extension = $archivo->getClientOriginalExtension();
	        $nombreArchivo = $filename . '_' . time() . '.' . $extension;
	        $rutaDB = $ruta . $nombreArchivo;

	        $guardar = Storage::put($ruta . $nombreArchivo, (string) file_get_contents($archivo), 'public');

	        if ($guardar) {
	            return ['estado' =>  'success', 'archivo' => $rutaDB];
	        } else {
	            return ['estado' =>  'failed', 'mensaje' => 'error al guardar el archivo.'];
	        }
	    }catch (\Throwable $t) {
    			return ['estado' =>  'failed', 'mensaje' => 'error al guardar el archivo, posiblemente este dañado o no exista.'];
		}
    }

    //metodos para actualizar datos en la cuenta sindical

    //@ este metodo recibe desde el front-end Objeto [ string campo, Input input,  Integer id]
    public function actualizar_dato_cs(Request $r)
    {
    	$cs = Cuentasindicato::find($r->id);

    	switch ($r->campo) {
    		case 'fecha':

    				$f = $this->div_fecha($r->input);
    				$anio = $this->anio_tipo_id($f['anio']);

    				if($cs->anio_id == $anio->id && $cs->mes_id == $f['mes']){

    					$cs->anio_id = $anio->id/*$f['anio']*/;
						$cs->mes_id  = $f['mes'];
						$cs->dia     = $f['dia'];

						if ($cs->save()) {
							return ['estado'=>'success','mensaje'=>'Fecha actualizada'];
						}
						return ['estado'=>'failed','mensaje'=>'error al actualizar'];

    				}else{
    				return ['estado'=>'failed','mensaje'=>'La fecha ingresada no pertenece al mes correspondiente'];
    				}
						

    		break;
    		case 'numero_documento':

    				$exist = Cuentasindicato::where([
    					'activo'=>'S', 
    					'numero_documento'=>$r->input,
    					'id'=>$r->id
    				])->first();

    				if ($exist) {
    					$exist->numero_documento = $r->input;
    					if ($exist->save()) {
    						return ['estado'=>'success','mensaje'=>'Numero de documento actualizado'];
    					}
    					return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    				}


    		break;
    		case 'tipo_cuenta_sindicato':
    			
    			$cs->tipo_cuenta_sindicato = $r->input;
    			if ($cs->save()) {
    				return ['estado'=>'success','mensaje'=>'Tipo de cuenta actualizada'];
    			}
    			return ['estado'=>'failed','mensaje'=>'error al actualizar'];

    		break;
    		case 'descripcion':
    			
    			$cs->descripcion = $r->input;
    			if ($cs->save()) {
    				return ['estado'=>'success','mensaje'=>'Descripción actualizada'];
    			}
    			return ['estado'=>'failed','mensaje'=>'error al actualizar'];

    		break;
    		case 'definicion':

    			if ($r->input == 1) {
    				$monto = $cs->monto_egreso;

    				$cs->monto_ingreso = $monto;
    				$cs->definicion = $r->input;
    				if ($cs->save()) {
    					return ['estado'=>'success','mensaje'=>'Definición actualizada'];
    				}
    				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    			}

    			if ($r->input == 2) {
    				$monto = $cs->monto_ingreso;

    				$cs->monto_egreso = $monto;
    				$cs->definicion = $r->input;
    				if ($cs->save()) {
    					return ['estado'=>'success','mensaje'=>'Definición actualizada'];
    				}
    				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    			}
    			


    		break;
    		case 'monto':
    			
    			if ($cs->definicion == 1) {
    				$cs->monto_ingreso = $r->input;
    				if ($cs->save()) {
    					return ['estado'=>'success','mensaje'=>'Monto actualizado'];
    				}
    				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    			}
    			if ($cs->definicion == 2) {
    				$cs->monto_egreso = $r->input;
    				if ($cs->save()) {
    					return ['estado'=>'success','mensaje'=>'Monto actualizado'];
    				}
    				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    			}

    		break;

    		case 'archivo':
    			


    		break;

    		
    		default:
    			# code...
    			break;
    	}
    }


}
