<?php

namespace App\Http\Controllers;

use App\Cuentasindicato;
use App\Estadodiasueldo;
use Illuminate\Http\Request;
use App\Detalleinteresprestamo;
use App\Cs_gastos_operacionales;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Cs_gastos_operacionales_detalle;
use Illuminate\Support\Facades\Validator;

class CuentaSindicatoController extends Controller
{
	public $global_caja_chica = 100000;

	public function validar_pdf($request)
	{
		$val = Validator::make($request->all(), 
		 	[

	            'input' => 'required|mimes:pdf',
	        ],
	        [
	        	'input.required' => 'El PDF es necesario',
	        	'input.mimes' => 'El archivo no es PDF',
	        ]);

 
	        if ($val->fails()){ return ['estado' => 'failed_v', 'mensaje' => $val->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
	}
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
	            'archivo' => 'required|mimes:pdf',
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
			DB::beginTransaction();
			//si el ingreso es de tipo consorcio y es un egreso, rechazar el ingreso
			if ($r->tipo_cuenta_sindicato == "8" && $r->definicion == "2") {
				return [
					'estado'=>'failed',
					'mensaje'=>'El ingreso consorcio no puede ser egreso, solamente ingreso'
				];
			}

			$file = $this->guardarArchivo($r->archivo,'archivos_sindical/');

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
				$borrar = Storage::delete('/'.$archivo);
			return [ 'estado' =>'failed', 'mensaje'=>'Ya existe item caja chica en este mes' ];

			}
			
			
			else{

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
						$borrar = Storage::delete('/'.$archivo);
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
						

						if ($r->tipo_cuenta_sindicato == '6') {// si el item entrante es gasto operacional
							if ($this->existe_item_go($anio->id, $f['mes'])) {
							   return [
								   'estado' => 'failed',
								   'mensaje'=>'ya existe item de gasto operacional para este mes'
								];
							}
						}

						//COMENTAMOS AQUI PARA EVITAR VALIDAR SI EXISTE YA UN MONTO PARA LOS MILLONES EN CONSORCIO
						// if ($r->tipo_cuenta_sindicato == '8') {
							
						// 	$eds = $this->estado_dia_sueldo();

							
						// 	if ($eds['estado'] == 'failed') {
						// 		return [
						// 			'estado' => 'failed',
						// 			'mensaje' => 'Ya existe un monto en el item consorcio de tipo ingreso'
						// 		];
						// 	}
						// }

								
						$cs = new Cuentasindicato;
						$cs->anio_id = $anio->id/*$f['anio']*/;
						$cs->mes_id  = $f['mes'];
						$cs->dia     = $f['dia'];
						$cs->numero_documento  = $r->n_documento;
						$cs->archivo_documento = '/doc/archivo.pdf'; //valor por mientras
						$cs->tipo_cuenta_sindicato = $r->tipo_cuenta_sindicato;
						$cs->descripcion = $r->descripcion;

						$cs->archivo_documento = 'storage/'.$archivo; 
						if ($r->tipo_cuenta_sindicato == 5) {
							//si el tipo de cuenta es camping
							$cs->detalle_camping = 'S';
						}

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
										
										$borrar = Storage::delete('/'.$archivo);
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
							
							if($cs->tipo_cuenta_sindicato == "5"){
								switch ($cs->definicion) {
									case '1':
											
										//aqui muestra si existe un camping en esta fecha
										$v_existe_cmp = Cuentasindicato::where([
											'mes_id' => $f['mes'],
											'anio_id' => $anio->id,
											'activo' => 'S',
											'tipo_cuenta_sindicato' => '5',
											'definicion' => '1',
											'cuenta_sindicato.detalle_camping' => 'S'
										])->count('id');
										 //dd($v_existe_cmp > 0);

										if($v_existe_cmp > 0){

											$v_existe_cmp_i_total = Cuentasindicato::where([
												'mes_id' => $f['mes'],
												'anio_id' => $anio->id,
												'activo' => 'S',
												'definicion' =>'1',
												'detalle_camping' =>null,
												'tipo_cuenta_sindicato'=>'5'
											])->first();

											$sum_det_camping = Cuentasindicato::where([
															'mes_id' => $f['mes'],
															'anio_id' => $anio->id,
															'activo' => 'S',
															'definicion' =>'1',
															'detalle_camping' =>'S',
															'tipo_cuenta_sindicato'=>'5'
											])->sum('monto_ingreso');

											//dd(empty($v_existe_cmp_e_total));
											if (empty($v_existe_cmp_i_total)) {

													$camp_i = new Cuentasindicato;
													$camp_i->dia = '1';
													$camp_i->mes_id = $f['mes'];
													$camp_i->anio_id= $anio->id;
													$camp_i->numero_documento = '--';
													$camp_i->archivo_documento = '--';
													$camp_i->tipo_cuenta_sindicato = $r->tipo_cuenta_sindicato;
													$camp_i->descripcion = 'Detalle camping ingreso';
													$camp_i->monto_ingreso = $sum_det_camping;
													$camp_i->definicion = '1';
													$camp_i->user_crea = Auth::user()->id;
													$camp_i->activo = 'S';

													$camp_i->save();

												}else{
													$v_existe_cmp_i_total->monto_ingreso = $sum_det_camping;
													$v_existe_cmp_i_total->save();
												}

												
											}
									
									break;
									case '2':

											//aqui muestra si existe un camping en esta fecha
										$v_existe_cmp = Cuentasindicato::where([
											'mes_id' => $f['mes'],
											'anio_id' => $anio->id,
											'activo' => 'S',
											'definicion' => '2',
											'tipo_cuenta_sindicato' => '5',
											'cuenta_sindicato.detalle_camping' => 'S'
										])->count('id');
										//dd($v_existe_cmp > 0);

										if($v_existe_cmp > 0){

											$v_existe_cmp_e_total = Cuentasindicato::where([
												'mes_id' => $f['mes'],
												'anio_id' => $anio->id,
												'activo' => 'S',
												'definicion' =>'2',
												'detalle_camping' =>null,
												'tipo_cuenta_sindicato'=>'5'
											])->first();

											$sum_det_camping = Cuentasindicato::where([
															'mes_id' => $f['mes'],
															'anio_id' => $anio->id,
															'activo' => 'S',
															'definicion' =>'2',
															'detalle_camping' =>'S',
															'tipo_cuenta_sindicato'=>'5'
											])->sum('monto_egreso');

											//dd($v_existe_cmp_e_total);

											if (empty($v_existe_cmp_e_total)) {

													$camp_e = new Cuentasindicato;
													$camp_e->dia = '1';
													$camp_e->mes_id = $f['mes'];
													$camp_e->anio_id= $anio->id;
													$camp_e->numero_documento = '--';
													$camp_e->archivo_documento = '--';
													$camp_e->tipo_cuenta_sindicato = $r->tipo_cuenta_sindicato;
													$camp_e->descripcion = 'Detalle camping egreso';
													$camp_e->monto_egreso = $sum_det_camping;
													$camp_e->definicion = $r->definicion;
													$camp_e->user_crea = Auth::user()->id;
													$camp_e->activo = 'S';

													$camp_e->save();

											}else{
												$v_existe_cmp_e_total->monto_egreso = $sum_det_camping;
												$v_existe_cmp_e_total->save();
											}

												
											}
									break;
										
									default:
											# code...
								    break;
								}
							}

							if ($cs->tipo_cuenta_sindicato == "6") {
								if($this->guardar_monto_detalle_go($anio->id, $f['mes'], $r->monto, $cs->id)){
									DB::commit();
									return [
										'estado'  => 'success', 
										'mensaje' => "Item de cuenta sindical añadido"
									];
								}
								return [
										'estado'  => 'success', 
										'mensaje' => "Item de cuenta sindical añadido, pero el detalle de los gastos operacionales so se ha ingresado"
									];
							}
							
							
							if ($cs->tipo_cuenta_sindicato == "8") {//tipo consorcio
								
								 $eds = $this->estado_dia_sueldo();

								// 	 //dd($eds);
								// if ($eds['estado'] == 'success') {
									$e = new Estadodiasueldo;
									$e->cuenta_sindicato_id = $cs->id;
									$e->egreso_total = 'N'; // aun no llega a monto cero el item consorcio
									$e->directiva_id = $eds['directiva'];
									$e->save();
								// }


							}

							DB::commit();
							return [
								'estado'  => 'success', 
								'mensaje' => "Item de cuenta sindical añadido"
							];
						}
						DB::rollBack();
						$borrar = Storage::delete('/'.$archivo);
						return [
							'estado'  => 'failed', 
							'mensaje' => 'Algo salió mal, intente nuevamente'];
							}

				}
				else {
					DB::rollBack();
					return $this->validar_datos_cs($r);
				}
		

					
			}
		}catch(QueryException $e){
			DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
				'error' => $e
			];
		}
		catch(\Exception $e){
			DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
				'error' => $e
			];
		}
		
	}

	public function listar_cuenta_sindicato($anio, $mes)
	{

		// try{

			$cm_txt = 'c_s_cierre_mensual';
			
			$c_m = DB::table($cm_txt)->where(['activo' => 'S','anio_id' => $anio,'mes_id' => $mes,
		    	])->first();
			

			if(!empty($c_m->inicio_mensual)){
				$s_a = $c_m->inicio_mensual;

				$data = $this->cuenta_sindical($s_a, $anio, $mes);
			    

				return $data;
			}
		// }
		// catch(QueryException $e){
		// 	return[
		// 		'estado'  => 'failed', 
		// 		'mensaje' => 'QEx: No se ha podido traer la lista cuenta sindical',
		// 		'exception' => $e //este es solo pal desarrollador;
		// 	];
		// }
		// catch(\Exception $e){
		// 	return[
		// 		'estado'  => 'failed', 
		// 		'mensaje' => 'Ex: No se ha podido traer la lista cuenta sindical',
		// 		'exception' => $e //este es solo pal desarrollador;
		// 	];
		// }
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
			// $return['gasto_operacional']=[];
			$return['gastosOp']=[];
			$return['consorcio']=[];

			foreach ($listar as $key) {
				switch ($key->tipo_cuenta_sindicato) {
					case '1': $return['fijo'][] = $key; break;
					case '2': $return['variable'][] = $key; break;
					case '3': $return['caja_chica'][] = $key; break;
					case '4': $return['prestamo'][] = $key; break;
					case '5': $return['camping'][] = $key; break;
					// case '6': $return['gasto_operacional'][] = $key; break;
					case '6': $return['gastosOp'][] = $key; break;
					case '8': $return['consorcio'][] = $key; break;
					
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
	public function existe_dinero_mes_anterior_caja_chica($anio ='', $mes ='')
	{	
		if ($anio =='' && $mes=='') {
			return ['estado'=>"failed",'monto'=>0];
		}
		// dd($anio.'; '.$mes);
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


				$monto_a_guardar = /*$this->global_caja_chica - */$monto;
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
			try{
				$anio = DB::table('anio')->where(['activo'=>'S', 'descripcion'=> ($anio_anterior) ])->first();


				//dd($anio->id.'; mes_id:'.$mes_anterior);
				$monto = DB::table('cs_caja_chica')->where([
							'activo' => 'S',
							'anio_id' => $anio->id,
							'mes_id' => $mes_anterior
						])->sum('monto_egreso');



				$monto_a_guardar = /*$this->global_caja_chica - */$monto;
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
    public function DescargarArchivo($archivo)
    {
    

    		return Storage::download($archivo);
    		
    
    }

    //metodos para actualizar datos en la cuenta sindical

    //@ este metodo recibe desde el front-end Objeto [ string campo, Input input,  Integer id]
    public function actualizar_dato_cs(Request $r)
    {
    	$valida_pdf = $this->validar_pdf($r);
    

    	$cs = Cuentasindicato::where('id',$r->id)->first();
    	//dd($cs);

    	if ($r->input == '') {
    		return ['estado'=>'failed','mensaje'=>'Ingrese un valor'];
    	}

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
    					//'id'=>$r->id
    				])->first();
    				if(!empty($exist)){
    					return ['estado'=>'failed','mensaje'=>'Error, ya existe este numero de documento en la base de datos'];
    				}else{
    					$cs->numero_documento = $r->input;
    					if ($cs->save()) {
    						return ['estado'=>'success','mensaje'=>'Numero de documento actualizado'];
    					}
    				}

    				


    		break;
    		case 'tipo_cuenta_sindicato':
    			
    			$mes =	$cs->mes_id;
    			$anio = $cs->anio_id;

    			if ($r->input == 3) {//si el input viene como caja chica
    				$verifi_cch = Cuentasindicato::where([
    							'tipo_cuenta_sindicato' => 3, //caja chica
    							'mes_id' => $mes,
    							'anio_id' => $anio,
    							'activo' => 'S'
    						])->first();

	    			//verificar que exista ya una caja chica en este mes
	    			if(!empty($verifi_cch)){ // si existe
	    				return ['estado'=>'failed','mensaje'=>'Error, ya existe una caja chica en este mes'];
	    			}


	    			$cs->tipo_cuenta_sindicato = $r->input;
	    			if ($cs->save()) {
	    				return ['estado'=>'success','mensaje'=>'Tipo de cuenta actualizada'];
	    			}
	    			return ['estado'=>'failed','mensaje'=>'error al actualizar'];

    			}
    			else{

    				$cs->tipo_cuenta_sindicato = $r->input;
	    			if ($cs->save()) {
	    				return ['estado'=>'success','mensaje'=>'Tipo de cuenta actualizada'];
	    			}
	    			return ['estado'=>'failed','mensaje'=>'error al actualizar'];

    			}

    		break;
    		case 'descripcion':
    			
    			$cs->descripcion = $r->input;
    			if ($cs->save()) {
    				return ['estado'=>'success','mensaje'=>'Descripción actualizada'];
    			}
    			return ['estado'=>'failed','mensaje'=>'error al actualizar'];

    		break;
    		case 'definicion':
    			if ($cs->definicion == $r->input) {
    				return ['estado'=>'failed','mensaje'=>'Ya existe esta definiciósn'];
    			}

    			if ($r->input == 1) {
    				$monto = $cs->monto_egreso;

    				$cs->monto_ingreso = $monto;
    				$cs->monto_egreso = null;
    				$cs->definicion = $r->input;
    				if ($cs->save()) {
    					return ['estado'=>'success','mensaje'=>'Definición actualizada'];
    				}
    				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    			}

    			if ($r->input == 2) {
    				$monto = $cs->monto_ingreso;

    				$cs->monto_egreso = $monto;
    				$cs->monto_ingreso = null;
    				$cs->definicion = $r->input;
    				if ($cs->save()) {
    					return ['estado'=>'success','mensaje'=>'Definición actualizada'];
    				}
    				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    			}
    			


    		break;
    		case 'definicion_camping':
    			if ($cs->definicion == $r->input) {
    				return ['estado'=>'failed','mensaje'=>'Ya existe esta definiciósn'];
    			}

    			if ($r->input == 1) {
    				$monto = $cs->monto_egreso;
    				//dd("1: ".$monto);

    				$cs->monto_ingreso = $monto;
    				$cs->monto_egreso = null;
    				$cs->definicion = $r->input;
    				if ($cs->save()) {

    					//$this->calcular_total_camping($cs);
    					return ['estado'=>'success','mensaje'=>'Definición actualizada'];
    				}
    				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    			}

    			if ($r->input == 2) {
    				$monto = $cs->monto_ingreso;
    				//dd("2: ".$monto);

    				$cs->monto_egreso = $monto;
    				$cs->monto_ingreso = null;
    				$cs->definicion = $r->input;
    				if ($cs->save()) {

    					//$this->calcular_total_camping($cs);

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
    			if($valida_pdf['estado'] == 'success'){
    			 $ruta = substr($cs->archivo_documento, 8);
				//$ruta = $cs->archivo_documento;
					
                    $borrar = Storage::delete($ruta);

                    if ($borrar) {
                        $guardarArchivo = $this->guardarArchivo($r->input, 'archivos_sindical/');

                        if ($guardarArchivo['estado'] == "success") {
                            $cs->archivo_documento = 'storage/'.$guardarArchivo['archivo'];
                            if ($cs->save()) {
                                return ['estado' => 'success', 'mensaje' => 'Archivo Modificado'];
                            } else {
                                return ['estado' => 'failed', 'mensaje' => 'Error al actualizar'];
                            }
                        } else {
                            return $guardarArchivo;
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'No se pudo actualizar el archivo'];
                    }
                }else{
                	return ['estado' => 'failed', 'mensaje' => 'El archivo no es un PDF o no existe un formato'];
                }

			break;
			//actualizar monto de gasto operacional
			case 'monto_go':

				$cs->monto_egreso = $r->monto_go;
				

			break;
    		
    		default:
    			# code...
    			break;
    	}
	}
	
	// public function actualizar_dato_cs(Request $r)
    // {
    

    // 	$cs = Cuentasindicato::where('id',$r->id)->first();
    // 	//dd($cs);

    // 	if ($r->input == '') {
    // 		return ['estado'=>'failed','mensaje'=>'Ingrese un valor'];
    // 	}

    // 	switch ($r->campo) {
    		
    		
    // 		case 'monto':
    			
    // 			if ($cs->definicion == 1) {
    // 				$cs->monto_ingreso = $r->input;
    // 				if ($cs->save()) {
    // 					return ['estado'=>'success','mensaje'=>'Monto actualizado'];
    // 				}
    // 				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    // 			}
    // 			if ($cs->definicion == 2) {
    // 				$cs->monto_egreso = $r->input;
    // 				if ($cs->save()) {
    // 					return ['estado'=>'success','mensaje'=>'Monto actualizado'];
    // 				}
    // 				return ['estado'=>'failed','mensaje'=>'error al actualizar'];
    // 			}

    // 		break;

    		
    		
    // 		default:
    // 			# code...
    // 			break;
    // 	}
    // }



    public function dinero_mes_anterior_caja_chica_2($anio, $mes)
	{	
		
		$convert = DB::table('anio')->where('id', $anio)->first();
		$anio = $convert->descripcion;


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


				$monto_a_guardar = /*$this->global_caja_chica - */$monto;
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
			try{
				$anio = DB::table('anio')->where(['activo'=>'S', 'descripcion'=> ($anio_anterior) ])->first();


				//dd($anio->id.'; mes_id:'.$mes_anterior);
				$monto = DB::table('cs_caja_chica')->where([
							'activo' => 'S',
							'anio_id' => $anio->id,
							'mes_id' => $mes_anterior
						])->sum('monto_egreso');



				$monto_a_guardar = /*$this->global_caja_chica - */$monto;
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

		// if($monto_sobrante < $this->global_caja_chica ){
		// 	return ['estado'=>'success', 'monto'=>$monto_sobrante];
		// }else{
		// 	return ['estado'=>'failed', 'monto'=> 'monto muy elevado a '.$this->global_caja_chica ];
		// }
		

	}

	public function listar_interes_prestamo($anio, $mes)
	{
		return Detalleinteresprestamo::traer_lista($anio, $mes);
	}

	public function calcular_total_camping($cs)
	{
		if($cs->tipo_cuenta_sindicato == "5"){
								switch ($cs->definicion) {
									case '1':
											
										//aqui muestra si existe un camping en esta fecha
										$v_existe_cmp = Cuentasindicato::where([
											'mes_id' => $cs->mes_id,
											'anio_id' => $cs->anio_id,
											'activo' => 'S',
											'tipo_cuenta_sindicato' => '5',
											'definicion' => '1',
											'cuenta_sindicato.detalle_camping' => 'S'
										])->count('id');
										 //dd($v_existe_cmp > 0);

										if($v_existe_cmp > 0){

											$v_existe_cmp_i_total = Cuentasindicato::where([
												'mes_id' => $cs->mes_id,
											    'anio_id' => $cs->anio_id,
												'activo' => 'S',
												'definicion' =>'1',
												'detalle_camping' =>null,
												'tipo_cuenta_sindicato'=>'5'
											])->first();

											$sum_det_camping = Cuentasindicato::where([
															'mes_id' => $cs->mes_id,
											                'anio_id' => $cs->anio_id,
															'activo' => 'S',
															'definicion' =>'1',
															'detalle_camping' =>'S',
															'tipo_cuenta_sindicato'=>'5'
											])->sum('monto_ingreso');

											//dd(empty($v_existe_cmp_e_total));
											if (empty($v_existe_cmp_i_total)) {

													$camp_i = new Cuentasindicato;
													$camp_i->dia = '1';
													$camp_i->mes_id = $cs->mes_id;
													$camp_i->anio_id= $cs->anio_id;
													$camp_i->numero_documento = '--';
													$camp_i->archivo_documento = '--';
													$camp_i->tipo_cuenta_sindicato = $r->tipo_cuenta_sindicato;
													$camp_i->descripcion = 'Detalle camping ingreso';
													$camp_i->monto_ingreso = $sum_det_camping;
													$camp_i->definicion = '1';
													$camp_i->user_crea = Auth::user()->id;
													$camp_i->activo = 'S';

													$camp_i->save();

												}else{
													$v_existe_cmp_i_total->monto_ingreso = $sum_det_camping;
													$v_existe_cmp_i_total->save();
												}

												
											}
									
									break;
									case '2':

											//aqui muestra si existe un camping en esta fecha
										$v_existe_cmp = Cuentasindicato::where([
											'mes_id' => $cs->mes_id,
											'anio_id' => $cs->anio_id,
											'activo' => 'S',
											'definicion' => '2',
											'tipo_cuenta_sindicato' => '5',
											'cuenta_sindicato.detalle_camping' => 'S'
										])->count('id');
										//dd($v_existe_cmp > 0);

										if($v_existe_cmp > 0){

											$v_existe_cmp_e_total = Cuentasindicato::where([
												'mes_id' => $cs->mes_id,
												'anio_id' => $cs->anio_id,
												'activo' => 'S',
												'definicion' =>'2',
												'detalle_camping' =>null,
												'tipo_cuenta_sindicato'=>'5'
											])->first();

											$sum_det_camping = Cuentasindicato::where([
															'mes_id' => $cs->mes_id,
															'anio_id' => $cs->anio_id,
															'activo' => 'S',
															'definicion' =>'2',
															'detalle_camping' =>'S',
															'tipo_cuenta_sindicato'=>'5'
											])->sum('monto_egreso');

											//dd($v_existe_cmp_e_total);

											if (empty($v_existe_cmp_e_total)) {

													$camp_e = new Cuentasindicato;
													$camp_e->dia = '1';
													$camp_e->mes_id = $cs->mes_id;
													$camp_e->anio_id= $cs->anio_id;
													$camp_e->numero_documento = '--';
													$camp_e->archivo_documento = '--';
													$camp_e->tipo_cuenta_sindicato = $r->tipo_cuenta_sindicato;
													$camp_e->descripcion = 'Detalle camping egreso';
													$camp_e->monto_egreso = $sum_det_camping;
													$camp_e->definicion = $r->definicion;
													$camp_e->user_crea = Auth::user()->id;
													$camp_e->activo = 'S';

													$camp_e->save();

											}else{
												$v_existe_cmp_e_total->monto_egreso = $sum_det_camping;
												$v_existe_cmp_e_total->save();
											}

												
											}
									break;
										
									default:
											# code...
								    break;
								}
						}
	}


	function existe_item_go($anio, $mes)
	{
		$go = Cuentasindicato::where([
			'mes_id' => $mes,
			'anio_id' => $anio,
			'tipo_cuenta_sindicato' => '6'
		])->first();

		if ($go) {
			return true;
		}
		return false;
	}

	public function guardar_monto_detalle_go($anio, $mes, $monto, $cs_id)
	{
		//directiva responsable
		$directiva = $this->directiva();

		$go = new Cs_gastos_operacionales_detalle;
		$go->cs_cuenta_sindicato_id = $cs_id;
		$go->mes_id = $mes;
		$go->anio_id = $anio;
		$go->descripcion = 'Monto inicial para gastos '.number_format($monto,0,'.',',').' pesos';
		$go->monto = $monto;
		$go->activo = 'S';
		$go->directiva = $directiva;
		if ($go->save()) {
			return true;
		}
		return false;

	}
	

	public function directiva()
	{
		$data = DB::table('directiva')->select('id')->where('activo','S')->first();
		return $data->id;
	}

	public function estado_dia_sueldo()
	{
		$directiva = DB::table('directiva')->where('activo','S')->first();

		$eds = DB::table('estado_dia_sueldos')->get()->last();
		
			if (empty($eds)) {
				return [ 'estado' => 'success', 'directiva' => $directiva->id ];
			}else{

				if ($eds->egreso_total == 'S') {
					return [ 'estado' => 'success', 'directiva' => $directiva->id ];
				}

				if ($eds->egreso_total == 'N') {
					return [ 'estado' => 'failed', 'directiva' => $directiva->id ];
				}

			}
	
		
	}


	//ALERTAAAAAA...
	//ESTE METODO YA ESTA OBSOLETO, EN LA TABLA ESTADO_DIA_SUELDO LA COLUMNA EGRESO_TOTAL NO ES UTILIZADA
	public function cerrar_estado_dia_sueldo($cs_id)
	{

		$e = Estadodiasueldo::where([
			'cuenta_sindicato_id' => $cs_id,
			'egreso_total' => 'N'
		])->first();

		if ($e) {
			$e->egreso_total = 'S'; // aqui se concidera el cierre del total de ahorro de dia de sueldo;
			//tambien el admin puede cerrar el proceso cuando este esté en onto 0, pero igual puede cerrar antes
			if ($e->save()) {
				return [
					'estado' => 'success',
					'mensaje' => 'Estado del total de ahorro de día de sueldo se ha cerrado con exito, ahora puede ingresar nuevamente el total de ahorro de dia de sueldos en la cuenta sindical'
				];
			}
		}
		else{
			return [
					'estado' => 'failed',
					'mensaje' => 'Estado del total de ahorro de día de sueldo no existe o ya está cerrado'
				];
		}
	}

	


}
