<?php

namespace App\Http\Controllers;

use App\Socios;
use App\CcPagoBeneficio;
use App\CuentaConsorcio;
use Illuminate\Http\Request;
use App\Consorciopagodiasueldo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CuentaConsorcioController extends Controller
{
    public function traer_socios_consorcios()
    {
        return CuentaConsorcio::listar_formulario();
    }

    public function filtrar($search='')
    {
        if ($search == '') {
            $this->listar();
        }
        $return = CuentaConsorcio::filtrar($search);
        return Response()->json($return);
    }

    public function insertar(Request $r)
    {
      ///VALIDAR SI ESTA DESVINCULADO EL SOCIO/**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
      /**/  //$desv = DB::select("SELECT                                                                /**/
      /**/    //                      concat(nombres,' ',a_paterno,' ',a_materno) nombre                /**/
      /**/     //                 FROM socios where id = $r->socio_id and fecha_egreso is not null");   /**/
      /**/  //if (count($desv)>0) {                                                                     /**/
      /**/    //  $nombre = $desv[0]->nombre;                                                           /**/
      /**/    //  return ['estado' => 'failed', 'mensaje'=> $nombre.' se ha desvinculado'];             /**/
      /**/  //}                                                                                         /**/
      /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

      // si no pues sigue su camino ->

        switch ($r->tipo_consorcio) {
            case '1': // DIA DE SUELDO
                $ds = CuentaConsorcio::insertar_ds($r);
                return $ds;
            break;

            case '2': // DESCUENTOS

                $ds = CuentaConsorcio::insertar_cex($r);
                return $ds;
            break;
            
            default:
                # code...
                break;
        }



    }


    public function insertar_cex_desde_tabla(Request $r)
    {
        $cc_id ='';
        $mes ='';
        $tipo_pago = '2';
        $tipo_retiro ='';
        $porcentaje='';
        $monto='';

        $ds = CuentaConsorcio::insertar_cex_desde_table($r);
        return $ds;
    
        
    }

    public function cuenta_consorcio($anio_id)
    {
        $listar = CuentaConsorcio::tabla_consorcio($anio_id);
        if ($listar != '') {
            foreach ($listar as $key) {
                
                if ($key->vinculado == 'N') {
                    $ultimo_ds = CuentaConsorcio::calcular_dia_sueldo($key->socio_id);
                    //$key->monto_total_menos_ds = (int)$key->monto_total_socio - (int)$ultimo_ds['dia_sueldo'];
                    //$key->monto_total_menos_ds = (int)$key->monto_total_ds_socio - (int)$ultimo_ds['dia_sueldo'];
                    $key->monto_total_menos_ds = (int)$key->monto_total_ds_socio - (int)$ultimo_ds['pago_beneficio'];

                }
            }

            return $listar;
        }
        
    }
    public function totales_cuenta_consorcio($anio_id)
    {

        return CuentaConsorcio::totales_mensuales_anual($anio_id);
    }

    public function total_anual_socio($anio_id)
    {
        return CuentaConsorcio::total_anual_socio($anio_id);
    }

    public function filtrar_tabla_consorcio($anio_id, $search='')
    {
        return CuentaConsorcio::filtrar_tabla_consorcio($anio_id, $search);
    }
    // TABLA DESVINCULADOS

    public function tabla_desvinculados($anio_id)
    {
         $listar = CuentaConsorcio::tabla_desvinculados($anio_id);

         if ($listar != '') {
            foreach ($listar as $key) {
                if ($key->vinculado == 'N') {
                    $ultimo_ds = CuentaConsorcio::calcular_dia_sueldo($key->socio_id);
                    //$key->monto_total_menos_ds = (int)$key->monto_total_socio - (int)$ultimo_ds['dia_sueldo'];
                    $key->monto_total_menos_ds = (int)$key->monto_total_ds_socio - (int)$ultimo_ds['dia_sueldo'];
                }
            }

            return $listar;
        }

    }

    public function calcular_dia_sueldo($socio_id, $porc, $desc, $mes)
    {
        $dia_sueldo = CuentaConsorcio::calcular_dia_sueldo($socio_id);

        $ds = (int)$dia_sueldo->dia_sueldo;
        $mult = $ds * $porc;
        $result = $ds - $mult;
        echo $result; 
         return ['ds' => $ds,'porc ' => $result];

        // content
    }
    public function calcular_dia_sueldo_manual($dia_sueldo, $porc, $desc, $mes)
    {

        $ds = (int)$dia_sueldo;
        $mult = $ds * $porc;
        $result = $ds - $mult;
        echo $result; 
         return ['ds' => $ds,'porc ' => $result];

        // content
    }

    public function socios_sin_pb()
    {
        $listar = Socios::select([
            'id',
            DB::raw("concat(nombres,' ',a_paterno,' ',a_materno,' - egreso: ',to_char(fecha_egreso, 'dd-mm-yyyy')) as sin_pb")
        ])->where([
            'retiro_pago_beneficio' => 'N'
        ])->get();

        if (count($listar)>0) {
            return $listar;
        }
    }

    public function descontar_dia_sueldo($porc, $mes, $anio)
    {

        $cc = CuentaConsorcio::select([
            'socio_id',
            'monto_mes_ds_'.$mes
        ])
        ->where([
            'anio_id' => $anio
        ])->get();
        
        $sum=0;
        $monto_beneficio =0;

        foreach ($cc as $key) {

            $sin_benef = DB::select("SELECT * from socios 
                                where (retiro_pago_beneficio ='N'
                                or retiro_pago_beneficio is null)   
                                and id = $key->socio_id");


                        
                            
            if (count($sin_benef) > 0) {
                if ($key['monto_mes_ds_'.$mes] != 0 || $key['monto_mes_ds_'.$mes] != null ) {
                        
                        $update = CuentaConsorcio::where([
                            'socio_id' => $key->socio_id,
                            'anio_id' => $anio
                        ])->first();


                        $ds = (int) $key['monto_mes_ds_'.$mes];
                        $mult = $ds * $porc;
                        $result = $mult; //$ds - $mult;

                        $update['monto_mes_cex_'.$mes] = round($result);
                        $monto_beneficio = $monto_beneficio + round($result);
                        if ($update->save()) {
                            $sum++;
                        }
                }
            }

            
        }

    
        if ($sum > 0) {
            return [
                'estado'=>'success',
                'mensaje'=>'Se han calculado '.$sum.' registro(s)',
                'monto_beneficio' => $monto_beneficio
            ];
        }
        return ['estado'=>'failed','mensaje'=>'ningun dato calculado'];

    }
    public function aplicar_descuento_dia_sueldo($socio_id, $porc, $desc, $mes, $anio, $monto=0)
    {
        try{
            // if ($monto == 0) {
            //     return [ 'estado'=>'failed', 'mensaje'=>'Ingrese un monto para guardar' ];
            // }


            DB::beginTransaction();
            
            $sum =0;
            $verify = CcPagoBeneficio::where([
                        'anio_id' =>$anio,
                        'mes_id' => $mes,
                        'socio_id' => $socio_id
                    ])->first();
            
            if ($verify) {  

                // if ($verify->descripcion != $desc) {
                //     return ['estado'=>'failed','mensaje'=>'Ya no puede cambiar el tipo de descuento'];
                // }

                $verify->mes_id = $mes;
                $verify->descripcion = $desc;
                $verify->porcentaje = $porc;
                $verify->monto_dia_sueldo = $monto;
                $verify->save();
                $sum++;

            }else{
                $pb = new CcPagoBeneficio;
                $pb->socio_id = $socio_id;
                $pb->anio_id = $anio;
                $pb->mes_id = $mes;
                $pb->descripcion = $desc;
                $pb->porcentaje = $porc;
                $pb->monto_dia_sueldo = $monto;
                $pb->save();
                $sum++;

            }

            $desv = CuentaConsorcio::where(['anio_id'=> $anio, 'socio_id'=>$socio_id])->first();
            $desv->vinculado = 'N';
            $desv->save();
            $sum++;

            if ($sum == 2) {
                $socio = Socios::find($socio_id);
                $socio->retiro_pago_beneficio = 'S'; //Se le dio su beneficio
                $socio->save();

                DB::commit();
                return [
                    'estado' => 'success',
                    'mensaje' => 'Pago de beneficio y desvinculacion exitosa'
                ];
            }
            return [
                    'estado' => 'failed',
                    'mensaje' => 'Pago de beneficio o desvinculacion erronea'
            ];
        }catch(QueryException $e){
            DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos'
			];
		}
		catch(\Exception $e){
            DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos'
			];
		}
    }

    public function descripcion_descuento($anio, $mes)
    {
        $desc = CcPagoBeneficio::select([
            DB::raw("concat('Retiro por ',descripcion,', con un porcentaje de ',CAST (porcentaje AS float) *100,'% , el monto a dar es ') texto"),
            'monto_dia_sueldo',
            DB::raw("concat(nombres,' ',a_paterno) nombre")
        ])
        ->join('socios as s','s.id','socio_id')
        ->where([
            'anio_id' => $anio,
            'mes_id' => $mes
        ])->first();

        if ($desc) {
            return [
                'estado' =>'success',
                'mensaje' => 'Socio desvinculado: '.$desc->nombre.', '.$desc->texto.
                '$'.number_format($desc->monto_dia_sueldo,0,'.',',').' peso(s)'
            ];
        }
        return [
            'estado' => 'failed',
            'mensaje' => 'No hay Descuentos en este mes'
        ];
    }

    public function listar_pago_beneficios($anio)// tabla desvinculados
    {
        $t_pb = CuentaConsorcio::listar_pago_beneficios($anio); // tabla desvinculados

        if ($t_pb != '') {
           
            foreach ($t_pb as $key) {
                
                $ds = CuentaConsorcio::desvincular_sumar_totales($anio, $key->socio_id);
    
                 $key->monto_h = $ds->monto_h;
                 $key->total = $key->monto_v + $ds->monto_h;
            }

            return $t_pb;
        }

    }



    public function proximo_periodo($anio_actual /*, $anio_sig*/)
    {
            try{
                DB::beginTransaction();

                $ano = DB::table("anio")->select([
                    DB::raw("(CAST(coalesce(descripcion, '0') AS integer) + 1) anio_sig")
                ])->where("id",$anio_actual)->first();
              
                $a = DB::table("anio")->select("id")->where("descripcion",$ano->anio_sig)->first();
                if(!$a){
                    return ['estado'=>'failed','mensaje'=>'El año siguiente no existe en la base de datos'];
                }

                $listar = CuentaConsorcio::tabla_consorcio($anio_actual);

                $sum = 0;
                $act_sum = 0;
                
                if ($listar != '') {
                    foreach ($listar as $key) {
                        if ($key->vinculado == 'S' || $key->vinculado == 'N') {
                            
                        //valido si exite ya el proximo periodo
                        $cc = CuentaConsorcio::where(['socio_id' => $key->socio_id,'anio_id' => $a->id])->first();
                        
                        if ($cc) {
                            // $cc->vinculado = $key->vinculado;
                            $cc->acumulado_anterior_socio = $key->monto_total_socio;
                            $cc->save();
                            $act_sum++;
                            DB::commit();
                            
                        }else{

                            $ncc = new CuentaConsorcio;
                            $ncc->socio_id = $key->socio_id;
                            $ncc->vinculado = $key->vinculado;
                            $ncc->acumulado_anterior_socio = $key->monto_total_socio;
                            $ncc->anio_id = $a->id;
                            $ncc->save();
                            $sum++;
                            DB::commit();

                        }


                        }
                    }

                    if ($sum > 0) {
                        return ['estado'=>'success', 'mensaje'=>'Se calcularon '.$sum.' registro(s), calculos actualizados '.$act_sum];
                    }
                    if ($act_sum > 0) {
                        return ['estado'=>'success', 'mensaje'=>'Se actualizaron '.$act_sum.' registro(s)'];
                    }
                    return ['estado'=>'failed', 'mensaje'=>'Error en pasar totales al proximo periodo'];
                }
                return ['estado'=>'failed', 'mensaje'=>'No se han encontrado datos en cuenta consorcio'];




            
            }catch(QueryException $e){
                DB::rollBack();
                return[
                    'estado'  => 'failed', 
                    'mensaje' => 'QEx: No se ha podido seguir con el proceso, intente nuevamente o verifique sus datos'
                ];
            }
		catch(\Exception $e){
                DB::rollBack();
                return[
                    'estado'  => 'failed', 
                    'mensaje' => 'Ex: No se ha podido seguir con el proceso, intente nuevamente o verifique sus datos'
                ];
		}
          
        
    }
    //monto total de los desvinculados segun periodo

    public function total_desvinculados($anio)// tabla desvinculados
    {   
        $tipo='sin_tipo';

        $periodo_init = DB::table('consorcio_directiva')->where('anio_id_inicio',$anio)->first();
        $periodo_fin = DB::table('consorcio_directiva')->where('anio_id_fin',$anio)->first();

        if ($periodo_init) {
            $tipo = 'inicio_periodo';
        }
        if ($periodo_fin) {
            $tipo = 'fin_periodo';
        }

        //dd($tipo);

        switch ($tipo) {
            case 'inicio_periodo':
                
                $i=0;
                $print=[];
                $total=0;

                $t_pb = CuentaConsorcio::listar_pago_beneficios($periodo_init->anio_id_inicio); // tabla desvinculados

                if ($t_pb != '') {
                
                    foreach ($t_pb as $key) {
                        
                        $ds = CuentaConsorcio::desvincular_sumar_totales($periodo_init->anio_id_inicio, $key->socio_id);
            
                        $key->monto_h = $ds->monto_h;
                        $key->total = $key->monto_v + $ds->monto_h;

                        $print[$i]['monto_h'] = $key->monto_h;
                        $total += $key->monto_h;
                        $i++;
                    }

                    return ['index'=>$print,'total'=>$total];
                }



            break;

            case 'fin_periodo':
                
                $i=0;
                $print=[];
                $total=0;
                

                $t_pb = CuentaConsorcio::listar_pago_beneficios_2($periodo_fin->anio_id_inicio, $periodo_fin->anio_id_fin); // tabla desvinculados
               
                if ($t_pb != '') {
                    //  dd($t_pb);
                    foreach ($t_pb as $key) {
                        
                        $ds = CuentaConsorcio::desvincular_sumar_totales_2($periodo_fin->anio_id_inicio, $periodo_fin->anio_id_fin, $key->socio_id);
            
                        $key->monto_h = $ds->monto_h;
                        $key->total = $key->monto_v + $ds->monto_h;

                        $print[$i]['monto_h'] = $key->monto_h;
                        $total += $key->monto_h;
                        $i++;
                    }

                    return ['index'=>$print,'total'=>(int)$total];
                }
                else{
                    return ['index'=>$print,'total'=>0];
                }



            break;
            
            default:
                return ['total'=>0];
                break;
        }

    }

    public function insertar_dia_sueldo_socio(Request $r)
    {
        $directiva = DB::table('directiva')->where('activo', 'S')->first();
        $validacion = $this->validar_datos_cs($r);
        $valida_pdf = $this->validar_pdf($r);
				
		if($validacion['estado'] == 'failed_v'){

            return [
                'estado' => 'failed',
                'mensaje' => 'No es posible hacer el ingreso, revise que no falten campos por llenar'
            ];

        }

        if($valida_pdf['estado'] == 'failed_v'){
            return [
                'estado' => 'failed',
                'mensaje' => 'El documento no existe o no es un PDF'
            ];
        }

        $verify = Consorciopagodiasueldo::where([
            'directiva_id' => $directiva->id,
            'socio_id' => $r->socio_id
        ])->first();

        //si existe socio en ese mismo periodo denegar
        if ($verify) {
            return [
                'estado'=>'failed',
                'mensaje' => 'Socio con su dia de sueldo pagado para esta directiva'
            ];

        }else{
        
            //insercion//////////////////////////////////////////////////////////////
            //
            $file = $this->guardarArchivo($r->archivo,'consorcio_dia_sueldos/');

            if($file['estado'] == "success"){
                $archivo = $file['archivo'];
            }else{
                return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
            }
            

            $cpds = new Consorciopagodiasueldo;
            $cpds->socio_id = $r->socio_id;
            $cpds->fecha = $r->fecha;
            $cpds->descripcion = $r->descripcion;
            $cpds->monto = $r->monto;
            $cpds->prestamo = $r->prestamo;
            $cpds->documento = 'storage/'.$archivo;
            $cpds->numero_documento = $r->numero_documento;
            $cpds->activo = 'S';

            if ($cpds->save()) {
            return [
                    'estado' => 'success',
                    'mensaje' => 'Socio con pago de dia de sueldo exitoso'
            ];
            }else{
                $borrar = Storage::delete('/'.$archivo);

                return [
                    'estado' => 'failed',
                    'mensaje' => 'Error en el proceso de guardado'
            ];
            }

            //insercion//////////////////////////////////////////////////////////////
        }
        
    }


    public function validar_pdf($request)
	{
		$val = Validator::make($request->all(), 
		 	[

	            'archivo_documento' => 'required|mimes:pdf',
	        ],
	        [
	        	'archivo_documento.required' => 'El PDF es necesario',
	        	'archivo_documento.mimes' => 'El archivo no es PDF',
	        ]);

 
	        if ($val->fails()){ return ['estado' => 'failed_v', 'mensaje' => $val->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
	}
	public function validar_datos_cs($request)
	{
		 $validator = Validator::make($request->all(), 
		 	[
	            'fecha' => 'required',
	            'numero_documento' => 'required|unique:consorcio_pago_dia_sueldo,numero_documento',
	            'descripcion' => 'required|min:0',
	            'prestamo' => 'required',
	            'monto' => 'required',
	            'documento' => 'required|mimes:pdf',
	        ],
	        [
	        	'fecha.required' => 'La fecha es necesaria',
	        	'numero_documento.required' => 'El numero de documento es necesario',
	        	'numero_documento.unique' => 'El numero de documento ya existe en tus registros',
                'descripcion.required' => 'La descripcion es necesaria',
                'prestamo.required' => 'El monto del prestamo es necesario',
                'monto.required' => 'El monto es necesario',
                'documento.required' => 'El documento es necesario',
	        ]);

 
	        if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
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
