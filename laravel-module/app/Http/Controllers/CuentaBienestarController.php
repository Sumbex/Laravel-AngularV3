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
	            'numero_documento_1' => 'required|unique:cuenta_bienestar,numero_documento',
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
	            'numero_documento_1' => 'required|unique:cuenta_bienestar,numero_documento',
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
                'numero_documento_1' => 'required|unique:cuenta_bienestar,numero_documento',
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


    public function insertar(Request $r)
    {

        $tipo_cuenta = $r->tipo_cuenta_bienestar_id;

        switch ((string)$tipo_cuenta) {
            case '1': // cuenta del gas
                $validar = $this->validar_datos_bienestar($r);
                if ($validar['estado'] == "success") {
                    $cuenta_be = Cuentabienestar::insertar_cuenta_sindical_gas($r);
                    return $cuenta_be;
                }
                return ['estado'=>'failed_v', 'mensaje'=>'Error de ingreso en el formulario, intente nuevamente'];

            break;
            case '2': //inasistencia por reunion
                $validar = $this->validar_datos_bienestar($r);
                if ($validar['estado'] == "success") {
                    $cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
                    return $cuenta_be;
                }
                return ['estado'=>'failed_v', 'mensaje'=>'Error de ingreso en el formulario, intente nuevamente'];
            break;
            case '3': // inasistencia por votacion

                $validar = $this->validar_datos_bienestar($r);
                if ($validar['estado'] == "success") {
                     $cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
                }
                     return $cuenta_be;
            break;
            case '6': // detalle caja chica
                $validar = $this->validar_datos_socio($r);
                if ($validar['estado'] == "success") {
                    $cuenta_be = Cuentabienestar::insertar_cuenta_sindical($r);
                    return $cuenta_be;
                }
                return ['estado'=>'failed_v', 'mensaje'=>'Error de ingreso en el formulario, intente nuevamente'];
            break;

            //-------------------------------------------------------------------------------------------------
            case '4': //Fallecimiento
                $validar = $this->validar_datos_fallecimiento($r);
                if ($validar['estado'] == "success") {
                    $fall = Cuentabienestar::insertar_fall_nac_gm($r);
                    return $fall;
                }
                return ['estado'=>'failed_v', 'mensaje'=>'Error de ingreso en el formulario, intente nuevamente'];
            break;
            case '5'://Nacimiento
                $validar = $this->validar_datos_nacimiento($r);
                if ($validar['estado'] == "success") {
                    $nac = Cuentabienestar::insertar_fall_nac_gm($r);
                    return $nac;
                }
                return ['estado'=>'failed_v', 'mensaje'=>'Error de ingreso en el formulario, intente nuevamente'];
            break;
            case '7'://Gastos medicos
                $gm = Cuentabienestar::insertar_fall_nac_gm($r);
                return $gm;
            break;
            default:
                
            break;
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

                //return $listar;

                $return = [];
                    $return['Cuenta_gas']=[];
                    $return['inasistencia_reunion']=[];
                    $return['inasistencia_votacion']=[];
                    $return['caja_chica']=[];
                    $return['nacimiento']=[];
                    $return['fallecimiento']=[];
                    $return['gastos_medicos']=[];

                    foreach ($listar as $key) {
                        switch ($key->tipo_cuenta_bienestar_id) {
                            case '1': $return['Cuenta_gas'][] = $key; break;
                            case '2': $return['inasistencia_reunion'][] = $key; break;
                            case '3': $return['inasistencia_votacion'][] = $key; break;
                            case '6': $return['caja_chica'][] = $key; break;
                            case '5': $return['nacimiento'][] = $key; break;
                            case '4': $return['fallecimiento'][] = $key; break;
                            case '7': $return['gastos_medicos'][] = $key; break;
                            
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

}
