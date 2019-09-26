<?php

namespace App;

use App\SocioConyuge;
use App\CbeNacimiento;
use App\CbeFallecimiento;
use App\SocioPadresSuegros;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cuentabienestar extends Model
{
    protected $table = "cuenta_bienestar";


    protected function insertar_cuenta_sindical_gas($r)//aqui cae la del gas
    {
        $f = $this->div_fecha($r->fecha);
        $anio = $this->anio_tipo_id($f['anio']);

         // PRIMERO VERIFICAR SI EXISTE 
        $exis_monto_init =  DB::table('cbe_cierre_mensual')->where([
								'activo'  => 'S',
								'anio_id' => $anio->id/*$f['anio']*/,
								'mes_id'  => $f['mes'],
                        ])->first();
        
        if(empty($exis_monto_init)){
			$borrar = Storage::delete('/'.$archivo);
			return [
				"estado"  => "failed", 
				"mensaje" => "No existe monto inicial, primero calcule"
			];

		}


        $verify = $this->where([
                'mes_id' => $f['mes'],
                'anio_id'=> $anio->id,
                'activo'=>'S',
                'tipo_cuenta_bienestar_id' => '1'
        ])->first();

        if (!empty($verify)) {
                return ['estado'=>'failed', 'mensaje'=>'Ya existe la cuenta del gas para este mes'];
        }
        
        $file = $this->guardarArchivo($r->archivo_documento_1,'archivos_bienestar/');

		if($file['estado'] == "success"){
                $archivo = $file['archivo'];
                

                $cbe = $this;
                $cbe->anio_id = $anio->id;
                $cbe->mes_id = $f['mes'];
                $cbe->dia = $f['dia'];
                $cbe->tipo_cuenta_bienestar_id = $r->tipo_cuenta_bienestar_id;
                $cbe->numero_documento_1 = $r->numero_documento_1;
                $cbe->archivo_documento_1 = 'storage/'.$archivo; /*$r->archivo_documento_1*/
                $cbe->definicion = $r->definicion;
                $cbe->activo = 'S';

                if ($r->definicion == '1') { $cbe->monto_ingreso = $r->monto; }
                if ($r->definicion == '2') { $cbe->monto_egreso = $r->monto; }

                $cbe->descripcion = $r->descripcion;
                
                if ($cbe->save()) {
                    return [ 'estado'=>'success', 'mensaje'=> 'Cuenta del gas ingresada correctamente' ];
                }
                $borrar = Storage::delete('/'.$archivo);
                return ['estado'=>'failed', 'mensaje'=>'Error al guardar item de cuenta del gas'];


		}else{
				return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
		}

    }
     protected function insertar_cuenta_sindical($r)//caja chica, reunion y votacion
    {
         $f = $this->div_fecha($r->fecha);
        $anio = $this->anio_tipo_id($f['anio']);

        if ($r->tipo_cuenta_bienestar_id == '6') { // si el ingreso es una caja chica

            $verify = $this->where([
                    'mes_id' => $f['mes'],
                    'anio_id'=> $anio->id,
                    'activo'=>'S',
                    'tipo_cuenta_bienestar_id' => '6'
            ])->first();

            if (!empty($verify)) {
                    return ['estado'=>'failed', 'mensaje'=>'Ya existe la caja chica para este mes'];
            }
        }

         // PRIMERO VERIFICAR SI EXISTE 
        $exis_monto_init =  DB::table('cbe_cierre_mensual')->where([
								'activo'  => 'S',
								'anio_id' => $anio->id/*$f['anio']*/,
								'mes_id'  => $f['mes'],
                        ])->first();
        
        if(empty($exis_monto_init)){
			$borrar = Storage::delete('/'.$archivo);
			return [
				"estado"  => "failed", 
				"mensaje" => "No existe monto inicial, primero calcule"
			];

		}



        $file = $this->guardarArchivo($r->archivo_documento_1,'archivos_bienestar/');

		if($file['estado'] == "success"){
            $archivo = $file['archivo'];

            $f = $this->div_fecha($r->fecha);
            $anio = $this->anio_tipo_id($f['anio']);

            $cbe = $this;
            $cbe->anio_id = $anio->id;
            $cbe->mes_id = $f['mes'];
            $cbe->dia = $f['dia'];
            $cbe->tipo_cuenta_bienestar_id = $r->tipo_cuenta_bienestar_id;
            $cbe->numero_documento_1 = $r->numero_documento_1;
            $cbe->archivo_documento_1 = 'storage/'.$archivo; /*$r->archivo_documento_1*/
            $cbe->definicion = $r->definicion;
            $cbe->activo = 'S';

            if ($r->definicion == '1') { $cbe->monto_ingreso = $r->monto; }
            if ($r->definicion == '2') { $cbe->monto_egreso = $r->monto; }

            $cbe->descripcion = $r->descripcion;

            if ($cbe->save()) {
                return [ 'estado'=>'success', 'mensaje'=> 'Item ingresado correctamente' ];
            }
            return ['estado'=>'failed', 'mensaje'=>'Error al guardar item'];


		}else{
			return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
		}
        
    }

    protected function insertar_fall_nac_gm($r)
    {

        $rut_limpio = $this->limpiar($r->rut);

        if(!$this->valida_rut($rut_limpio)){
                return ['estado'=>'failed','mensaje'=>'Rut no valido'];
        }


        switch ((string)$r->tipo_cuenta_bienestar_id) {
            case '5':
                 $verify = $this->verificar_existencia_nacimiento($r->socio_id, $rut_limpio);
                 if (!$verify) {
                    return [ 
                        'estado'=>'failed', 
                        'mensaje'=> 'El rut del recien nacido puede que no esté asociado al socio o el cobro del beneficio ya esta adquirido'
                     ];
                 }
            break;
            case '4':
                $verify = $this->verificar_existencia_fallecido($r->socio_id, $rut_limpio);
                if (!$verify) {
                    return [ 
                        'estado'=>'failed', 
                        'mensaje'=> 'El rut del fallecido puede que no exista en los beneficios o ya esta asociado al item Fallecimiento segun el socio'
                     ];
                }
            break;
            
            default:
                # code...
            break;
        }

        $f = $this->div_fecha($r->fecha);
        $anio = $this->anio_tipo_id($f['anio']);

         // PRIMERO VERIFICAR SI EXISTE 
        $exis_monto_init =  DB::table('cbe_cierre_mensual')->where([
								'activo'  => 'S',
								'anio_id' => $anio->id/*$f['anio']*/,
								'mes_id'  => $f['mes'],
                        ])->first();
        
        if(empty($exis_monto_init)){
			$borrar = Storage::delete('/'.$archivo);
			return [
				"estado"  => "failed", 
				"mensaje" => "No existe monto inicial, primero calcule"
			];

		}


        if ($r->tipo_cuenta_bienestar_id == '4') { // tipo fallecido

            $file = $this->guardarArchivo($r->archivo_documento_1,'archivos_bienestar/');
            $file2 = $this->guardarArchivo($r->archivo_documento_2,'archivos_bienestar/defuncion/');    
        
            if($file['estado'] == "success" && $file2['estado'] == "success"){

                $archivo = $file['archivo'];
                $archivo2 = $file2['archivo'];
                

                $cbe = $this;
                $cbe->anio_id = $anio->id;
                $cbe->mes_id = $f['mes'];
                $cbe->dia = $f['dia'];
                $cbe->tipo_cuenta_bienestar_id = $r->tipo_cuenta_bienestar_id;
                $cbe->numero_documento_1 = $r->numero_documento_1;
                $cbe->archivo_documento_1 = 'storage/'.$archivo; /*$r->archivo_documento_1*/
                $cbe->archivo_documento_2 = 'storage/'.$archivo2;
                $cbe->definicion = $r->definicion;
                $cbe->activo = 'S';

                if ($r->definicion == '1') { $cbe->monto_ingreso = $r->monto; }
                if ($r->definicion == '2') { $cbe->monto_egreso = $r->monto; }

                $cbe->descripcion = $r->descripcion;

                if ($cbe->save()) {

                    switch ((string)$cbe->tipo_cuenta_bienestar_id) {
                        case '4'://Fallecimiento
                            return CbeFallecimiento::insertar($cbe->id, $r->socio_id, $rut_limpio);
                        break;
                        case '5'://Nacimiento
                            return CbeNacimiento::insertar($cbe->id, $r->socio_id, $rut_limpio);
                        break;
                        case '7'://Gastos medicos
                            return CbeGastosMedicos::insertar($cbe->id, $r->socio_id);
                        break;
                        
                        default:
                            # code...
                        break;
                    }

                }
                return ['estado'=>'failed', 'mensaje'=>'Error al guardar item'];
            }else{
                    return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
            }

        }
        else{ // o si no guardado normal para nacimiento o gastos medicos

            $file = $this->guardarArchivo($r->archivo_documento_1,'archivos_bienestar/');
        
            if($file['estado'] == "success"){
                $archivo = $file['archivo'];
                

                $cbe = $this;
                $cbe->anio_id = $anio->id;
                $cbe->mes_id = $f['mes'];
                $cbe->dia = $f['dia'];
                $cbe->tipo_cuenta_bienestar_id = $r->tipo_cuenta_bienestar_id;
                $cbe->numero_documento_1 = $r->numero_documento_1;
                $cbe->archivo_documento_1 = 'storage/'.$archivo; /*$r->archivo_documento_1*/
                $cbe->definicion = $r->definicion;
                $cbe->activo = 'S';

                if ($r->definicion == '1') { $cbe->monto_ingreso = $r->monto; }
                if ($r->definicion == '2') { $cbe->monto_egreso = $r->monto; }

                $cbe->descripcion = $r->descripcion;

                if ($cbe->save()) {

                    switch ((string)$cbe->tipo_cuenta_bienestar_id) {
                        case '4'://Fallecimiento
                            return CbeFallecimiento::insertar($cbe->id, $r->socio_id, $rut_limpio);
                        break;
                        case '5'://Nacimiento
                            return CbeNacimiento::insertar($cbe->id, $r->socio_id, $rut_limpio);
                        break;
                        case '7'://Gastos medicos
                            return CbeGastosMedicos::insertar($cbe->id, $r->socio_id);
                        break;
                        
                        default:
                            # code...
                        break;
                    }

                }
                return ['estado'=>'failed', 'mensaje'=>'Error al guardar item'];
            }else{
                    return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
            }

        }


            
    }

    protected function listar($anio, $mes)
    {
        $list = DB::select("SELECT 
                        cbe.id,
                        concat(cbe.dia,' de ',m.descripcion,',',a.descripcion) as fecha,
                        numero_documento_1,
                        archivo_documento_1,
                        archivo_documento_2,
                        monto_ingreso,
                        monto_egreso,
                        definicion,
                        tcb.id as tipo_cuenta_bienestar_id,
                        tcb.descripcion as tipo_cuenta,
                        upper(cbe.descripcion) descripcion,
                        tcb.orden 
                    from cuenta_bienestar cbe
                    inner join anio as a on a.id = cbe.anio_id
                    inner join mes as m on m.id = cbe.mes_id
                    inner join tipo_cuenta_bienestar as tcb on tcb.id = cbe.tipo_cuenta_bienestar_id
                    where cbe.mes_id = $mes and cbe.anio_id= $anio and cbe.activo ='S'
                    --order by tcb.id ASC 
                    order by tcb.orden ASC, cbe.dia asc "
        );
        
        if ($list > 0 ) {
            return $list;
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

     protected function resultado_cuenta_sindical($anio, $mes)
	{
			$ingreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			// ->where('cuenta_bienestar.detalle_camping', null)
			->sum('monto_ingreso');
			$egreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			// ->where('cuenta_bienestar.detalle_camping', null)
			->sum('monto_egreso');
			$total = $ingreso_total - $egreso_total;

			$anterior = DB::table('cbe_cierre_mensual')->select('inicio_mensual')->where([
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



    // otros metodos utiles

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

     public function verificar_existencia_nacimiento($socio_id, $rut)
    {
        $exist_beneficiario = false;
        $existe_carga = false;
        
        $benef = SocioBeneficiario::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'
        ])->first();

        if (!empty($benef)) {
             $exist_beneficiario = true;
        }

        $carga = SocioCarga::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'

        ])->first();

        if (!empty($carga)) {
             $existe_carga = true;
        }

        if ($exist_beneficiario || $existe_carga) {

            $very = CbeNacimiento::where([
                'activo'=>'S','socio_id'=>$socio_id, 'rut_nacimiento'=>$rut
            ])->first();

            if (empty($very)) {
                return true;
            }else{
                return false;
            }
            
        }
        return false;

        
    }

    public function verificar_existencia_fallecido($socio_id, $rut)
    {
        $exist_beneficiario = false;
        $exist_carga = false;
        $exist_cony = false;
        $exist_p_s = false;
        
        $benef = SocioBeneficiario::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'
        ])->first();

            if (!empty($benef)) {
             $exist_beneficiario = true;
            }

        $carga = SocioCarga::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'

        ])->first();

            if (!empty($carga)) {
             $exist_carga = true;
            }

        $cony = SocioConyuge::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'
        ])->first();

            if (!empty($cony)) {
                $exist_cony = true;
            }
        $p_s = SocioPadresSuegros::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'
        ])->first();
            
         if (!empty($p_s)) {
                $exist_p_s = true;
            }
        
            
        if ($exist_beneficiario || $exist_carga || $exist_cony || $exist_p_s) {

            $very = CbeFallecimiento::where([
                'activo'=>'S','socio_id'=>$socio_id, 'rut_fallecido'=>$rut
            ])->first();

            if (empty($very)) {
                return true;
            }else{
                return false;
            }
            
        }
        return false;
    }

    function valida_rut($rut)
    {
        try{
            $rut = preg_replace('/[^k0-9]/i', '', $rut);
            $dv  = substr($rut, -1);
            $numero = substr($rut, 0, strlen($rut)-1);
            $i = 2;
            $suma = 0;
            foreach(array_reverse(str_split($numero)) as $v)
            {
                if($i==8)
                    $i = 2;
                $suma += $v * $i;
                ++$i;
            }
            $dvr = 11 - ($suma % 11);
            
            if($dvr == 11)
                $dvr = 0;
            if($dvr == 10)
                $dvr = 'K';
            if($dvr == strtoupper($dv))
                return true;
            else
                return false;
        }
        catch(\Exception $e){
            return false;
        }
    }
    

    function limpiar($s) 
    { 
        $s = str_replace('á', 'a', $s); 
        $s = str_replace('Á', 'A', $s); 
        $s = str_replace('é', 'e', $s); 
        $s = str_replace('É', 'E', $s); 
        $s = str_replace('í', 'i', $s); 
        $s = str_replace('Í', 'I', $s); 
        $s = str_replace('ó', 'o', $s); 
        $s = str_replace('Ó', 'O', $s); 
        $s = str_replace('Ú', 'U', $s); 
        $s= str_replace('ú', 'u', $s); 

        //Quitando Caracteres Especiales 
        $s= str_replace('"', '', $s); 
        $s= str_replace(':', '', $s); 
        $s= str_replace('.', '', $s); 
        $s= str_replace(',', '', $s); 
        $s= str_replace(';', '', $s); 
        $s= str_replace('-', '', $s); 
        return $s; 
    }
    

}
