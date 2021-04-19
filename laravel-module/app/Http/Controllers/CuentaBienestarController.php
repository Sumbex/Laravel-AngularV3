<?php

namespace App\Http\Controllers;

use App\Cuentabienestar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class CuentaBienestarController extends Controller
{

    public function validar_datos_bienestar($request)
	{
		 $validator = Validator::make($request->all(),
		 	[
	            'fecha' => 'required',
	            'numero_documento_1' => 'required|unique:cuenta_bienestar,numero_documento_1',
	            'descripcion' => 'required|min:0',
	            'definicion' => 'required|min:0',
	            'tipo_cuenta_bienestar_id' => 'required',
	            'monto' => 'required',
	            'archivo_documento_1' => 'required|mimes:pdf',
	        ],
	        [
	        	'fecha.required' => 'La fecha es necesaria',
	        	'numero_documento_1.required' => 'El numero de documento es necesario',
	        	'numero_documento_1.unique' => 'El numero de documento ya existe en tus registros',
	        	'descripcion.required' => 'La descripcion es necesaria',
	        	'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
	        	'tipo_cuenta_bienestar_id.required' => 'Especifique el tipo de cuenta de su detalle',
                'monto.required' => 'El monto es necesario',
                'archivo_documento_1.required' =>'El documento es necesario',
                'archivo_documento_1.mimes' =>'El documento debe ser un PDF'
	        ]);


	        if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
    }
    public function validar_datos_nacimiento($request)
	{
		 $validator = Validator::make($request->all(),
		 	[
	            'fecha' => 'required',
	            'numero_documento_1' => 'required|unique:cuenta_bienestar,numero_documento_1',
	            'descripcion' => 'required|min:0',
	            'definicion' => 'required|min:0',
	            'tipo_cuenta_bienestar_id' => 'required',
	            'monto' => 'required',
                'archivo_documento_1' => 'required|mimes:pdf',
                'rut' => 'required',
                'socio_id' => 'required'
	        ],
	        [
	        	'fecha.required' => 'La fecha es necesaria',
	        	'numero_documento_1.required' => 'El numero de documento es necesario',
	        	'numero_documento_1.unique' => 'El numero de documento ya existe en tus registros',
	        	'descripcion.required' => 'La descripcion es necesaria',
	        	'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
	        	'tipo_cuenta_bienestar_id.required' => 'Especifique el tipo de cuenta de su detalle',
                'monto.required' => 'El monto es necesario',
                'archivo_documento_1.required' =>'El documento es necesario',
                'archivo_documento_1.mimes' =>'El documento debe ser un PDF',
                'rut.required' => 'El rut del recien nacido es necesario',
                'socio_id.required' => 'El socio es necesario'
	        ]);


	        if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
    }
    public function validar_datos_fallecimiento($request)
	{
		 $validator = Validator::make($request->all(),
		 	[
	            'fecha' => 'required',
                'numero_documento_1' => 'required|unique:cuenta_bienestar,numero_documento_1',
                // 'numero_documento_2' => 'required|unique:cuenta_bienestar,numero_documento',
	            'descripcion' => 'required|min:0',
	            'definicion' => 'required|min:0',
	            'tipo_cuenta_bienestar_id' => 'required',
	            'monto' => 'required',
                'archivo_documento_1' => 'required|mimes:pdf',
                'archivo_documento_2' => 'required|mimes:pdf',
                'rut' => 'required',
                'socio_id' => 'required'
	        ],
	        [
	        	'fecha.required' => 'La fecha es necesaria',
	        	'numero_documento_1.required' => 'El numero de documento es necesario',
	        	'numero_documento_1.unique' => 'El numero de documento ya existe en tus registros',
	        	'descripcion.required' => 'La descripcion es necesaria',
	        	'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
	        	'tipo_cuenta_bienestar_id.required' => 'Especifique el tipo de cuenta de su detalle',
                'monto.required' => 'El monto es necesario',
                'archivo_documento_1.required' =>'El documento es necesario',
                'archivo_documento_1.mimes' =>'El documento debe ser un PDF',
                'archivo_documento_2.required' =>'El documento del fallecido es necesario',
                'archivo_documento_2.mimes' =>'El documento del fallecido debe ser un PDF',
                'rut.required' => 'El rut del recien nacido es necesario',
                'socio_id.required' => 'El socio es necesario'
	        ]);


	        if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
	}
	 public function validar_datos_gm($request)
	{
		 $validator = Validator::make($request->all(),
		 	[
	            'fecha' => 'required',
	            'numero_documento_1' => 'required|unique:cuenta_bienestar,numero_documento_1',
	            'descripcion' => 'required|min:0',
	            'definicion' => 'required|min:0',
	            'tipo_cuenta_bienestar_id' => 'required',
	            'monto' => 'required',
				'archivo_documento_1' => 'required|mimes:pdf',
				'archivo_documento_2' => 'required|mimes:pdf'
	        ],
	        [
	        	'fecha.required' => 'La fecha es necesaria',
	        	'numero_documento_1.required' => 'El numero de documento es necesario',
	        	'numero_documento_1.unique' => 'El numero de documento ya existe en tus registros',
	        	'descripcion.required' => 'La descripcion es necesaria',
	        	'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
	        	'tipo_cuenta_bienestar_id.required' => 'Especifique el tipo de cuenta de su detalle',
                'monto.required' => 'El monto es necesario',
                'archivo_documento_1.required' =>'El documento es necesario',
				'archivo_documento_1.mimes' =>'El documento debe ser un PDF',
				'archivo_documento_2.required' =>'El documento es necesario',
                'archivo_documento_2.mimes' =>'El documento debe ser un PDF'
	        ]);


	        if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
    }


    public function insertar(Request $r)
    {
		try{

			$tipo_cuenta = $r->tipo_cuenta_bienestar_id;

			switch ((string)$tipo_cuenta) {
				case '1': // cuenta del gas
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical_gas($r);
						return $cuenta_be;
					}
					return $validar;

				break;
				case '2': //inasistencia por reunion
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;
				case '3': // inasistencia por votacion

					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;
				case '6': // detalle caja chica
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;

				case '8': // 10% cuota socio
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;

				case '9': // Inasistencia por elecciones
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;

				case '10': // Consorcio
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;
				case '11': // Cuota extraordinaria
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;
				case '12': // No Sindicalizada
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;

				case '14': // Arreglo floral
					$validar = $this->validar_datos_bienestar($r);
					if ($validar['estado'] == "success") {
						$cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
						return $cuenta_be;
					}
					return $validar;
				break;

				//-------------------------------------------------------------------------------------------------
				case '4': //Fallecimiento
					$validar = $this->validar_datos_fallecimiento($r);
					if ($validar['estado'] == "success") {
						$fall = Cuentabienestar::insertar_fall_nac_gm($r);
						return $fall;
					}
					return $validar;
				break;
				case '5'://Nacimiento
					$validar = $this->validar_datos_nacimiento($r);
					if ($validar['estado'] == "success") {
						$nac = Cuentabienestar::insertar_fall_nac_gm($r);
						return $nac;
					}
					return $validar;
				break;
				case '7'://Gastos medicos
					$validar = $this->validar_datos_gm($r);
					if ($validar['estado'] == "success") {
						$gm = Cuentabienestar::insertar_fall_nac_gm($r);
						return $gm;
					}
					return $validar;
				break;
				default:

				break;
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

    public function listar_cuenta($anio, $mes)
    {
        $cm_txt = 'cbe_cierre_mensual';

		$c_m=DB::table($cm_txt)->where(['activo' => 'S','anio_id' => $anio,'mes_id' => $mes])->first();

		if(!empty($c_m->inicio_mensual)){
			$s_a = $c_m->inicio_mensual;

            $listar = Cuentabienestar::listar($anio, $mes);

            if ($listar > 0) {

                $tomar = true;

                for ($i=0; $i < count($listar); $i++) {

                    switch ($listar[$i]->definicion) {
                        case '1':

                            if ($tomar == true) {
								$listar[$i]->saldo_actual_raw = $s_a + $listar[$i]->monto_ingreso;
								//  dump($listar[$i]->saldo_actual_raw);
                                $tomar = false;

                            }else{
								$listar[$i]->saldo_actual_raw = $listar[$i-1]->saldo_actual_raw  + $listar[$i]->monto_ingreso;
								//  dump( $listar[$i-1]->saldo_actual_raw." + ".$listar[$i]->monto_ingreso." = ".$listar[$i]->saldo_actual_raw);
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

                // return $listar;

                $return = [];
                    $return['Cuenta_gas']=[];
                    $return['inasistencia_reunion']=[];
                    $return['inasistencia_votacion']=[];
                    $return['caja_chica']=[];
                    $return['nacimiento']=[];
                    $return['fallecimiento']=[];
					$return['gastos_medicos']=[];

					$return['socio_cuota']=[];
					$return['inasistencia_elecciones']=[];
					$return['consorcio']=[];
					$return['cuota_extraordinaria']=[];
					$return['no_sindicalizados']=[];
					$return['aporte']=[];
					$return['arreglo_floral']=[];

                    foreach ($listar as $key) {
                        switch ($key->tipo_cuenta_bienestar_id) {
                            case '1': $return['Cuenta_gas'][] = $key; break;
                            case '2': $return['inasistencia_reunion'][] = $key; break;
                            case '3': $return['inasistencia_votacion'][] = $key; break;
                            case '6': $return['caja_chica'][] = $key; break;
                            case '5': $return['nacimiento'][] = $key; break;
                            case '4': $return['fallecimiento'][] = $key; break;
							case '7': $return['gastos_medicos'][] = $key; break;

							case '8': $return['socio_cuota'][] = $key; break;
							case '9': $return['inasistencia_elecciones'][] = $key; break;
							case '10': $return['consorcio'][] = $key;  break;
							case '11': $return['cuota_extraordinaria'][] = $key;  break;
							case '12': $return['no_sindicalizados'][] = $key;  break;
							case '13': $return['aporte'][] = $key;  break;
							case '14': $return['arreglo_floral'][] = $key;  break;

                            default:
                                # code...
                            break;
                        }
					}

                    $return['resultado'] = Cuentabienestar::resultado_cuenta_sindical($anio, $mes);

                    return $return;
            }
            return ['estado'=>'failed','mensaje'=>'No hay datos en la tabla'];
        }
        return ['estado'=>'failed','mensaje'=>'No hay monto inicial en este mes'];

	}

	 public function actualizar_dato_cbe(Request $r)
    {
    	$valida_pdf = $this->validar_pdf($r);

    	$cs = Cuentabienestar::where('id',$r->id)->first();
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
    		case 'numero_documento_1':

    				$exist = Cuentabienestar::where([
    					'activo'=>'S',
    					'numero_documento_1'=>$r->input,
    					//'id'=>$r->id
    				])->first();
    				if(!empty($exist)){
    					return ['estado'=>'failed','mensaje'=>'Error, ya existe este numero de documento en la base de datos'];
    				}else{
    					$cs->numero_documento_1 = $r->input;
    					if ($cs->save()) {
    						return ['estado'=>'success','mensaje'=>'Numero de documento actualizado'];
    					}
    				}

			break;

    		case 'tipo_cuenta_bienestar':

    			$mes =	$cs->mes_id;
    			$anio = $cs->anio_id;

    			if ($r->input == 3) {//si el input viene como caja chica
    				$verifi_cch = Cuentabienestar::where([
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

    		case 'archivo_1':
    			if($valida_pdf['estado'] == 'success'){
    			 $ruta = substr($cs->archivo_documento_1, 8);
				//$ruta = $cs->archivo_documento;

                    $borrar = Storage::delete($ruta);

                    if ($borrar) {
                        $guardarArchivo = $this->guardarArchivo($r->input, 'archivos_bienestar/');

                        if ($guardarArchivo['estado'] == "success") {
                            $cs->archivo_documento_1 = 'storage/'.$guardarArchivo['archivo'];
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
			case 'archivo_2':
    			if($valida_pdf['estado'] == 'success'){
    			 $ruta = substr($cs->archivo_documento_2, 8);
				//$ruta = $cs->archivo_documento;

                    $borrar = Storage::delete($ruta);

                    if ($borrar) {
                        $guardarArchivo = $this->guardarArchivo($r->input, 'archivos_bienestar/defuncion/');

                        if ($guardarArchivo['estado'] == "success") {
                            $cs->archivo_documento_2 = 'storage/'.$guardarArchivo['archivo'];
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
			case 'archivo_2_gm':
    			if($valida_pdf['estado'] == 'success'){
    			 $ruta = substr($cs->archivo_documento_2, 8);
				//$ruta = $cs->archivo_documento;

                    $borrar = Storage::delete($ruta);

                    if ($borrar) {
                        $guardarArchivo = $this->guardarArchivo($r->input, 'archivos_bienestar/gastosmedicos/');

                        if ($guardarArchivo['estado'] == "success") {
                            $cs->archivo_documento_2 = 'storage/'.$guardarArchivo['archivo'];
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


    		default:
    			# code...
    			break;
    	}
    }
    //este metodo recibe el año como tal
	public function existe_dinero_mes_anterior_caja_chica($anio = '', $mes = '')
	{
		if ($anio == '' && $mes == '') {
			return ['estado'=>'success', 'monto'=>0];
		}

		// dd($anio.'; '.$mes);
		$mes_anterior = $mes - 1;
		$anio_anterior = $anio;

		if ($mes_anterior == 0) { //si la fecha capta el mes anterior (diciembre )tomar el valor del año tambien
			$mes_anterior = 12;
			$anio_anterior = $anio - 1;

			try{
				$anio = DB::table('anio')->where(['activo'=>'S', 'descripcion'=> "$anio_anterior" ])->get();

				$monto = DB::table('cb_caja_chica')->where([
							'activo' => 'S',
							'anio_id' => $anio->id,
							'mes_id' => $mes_anterior
						])->sum('monto_egreso');


				$monto_a_guardar = /*$this->global_caja_chica - */$monto;
				if ($monto_a_guardar == 100000) {
					return ['estado'=>'success', 'monto'=>0];
				}

				return ['estado'=>'success', 'monto'=>$monto_a_guardar];

			}catch (\Exception $e)
		    {

		        return ['estado'=>"failed",'monto'=>0];
		    }

		}
		else
		{
            try{
                $anio = DB::table('anio')->where(['activo'=>'S', 'descripcion'=> ($anio_anterior) ])->first();


                $monto = DB::table('cb_caja_chica')->where([
                            'activo' => 'S',
                            'anio_id' => $anio->id,
                            'mes_id' => $mes_anterior
                        ])->sum('monto_egreso');



                $monto_a_guardar = /*$this->global_caja_chica - */$monto;
                if ($monto_a_guardar == 100000) {
                    return ['estado'=>'success', 'monto'=>0];
                }
                return ['estado'=>'success', 'monto'=>$monto_a_guardar];

            }catch (\Exception $e)
		    {

		        return ['estado'=>"failed",'monto'=>0];
		    }


		}

    }

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

				$monto = DB::table('cb_caja_chica')->where([
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
				$monto = DB::table('cb_caja_chica')->where([
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

	}


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
}
