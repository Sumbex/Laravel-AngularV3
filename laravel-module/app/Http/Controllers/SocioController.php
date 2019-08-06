<?php

namespace App\Http\Controllers;

use App\Socio_datos_basicos;
use App\Socios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SocioController extends Controller
{
    public function validar_datos_socio($request)
    {
         $validator = Validator::make($request->all(), 
            [
                'rut' => 'required',
                'fecha_nacimiento' => 'required|date',
                'fecha_ingreso' => 'required|date',
                'nombres' => 'required|min:0',
                'a_paterno' => 'required',
                'a_materno' => 'required',
                //'archivo' => 'required|mimes:doc,pdf,docx',
            ],
            [
                'rut.required' => 'El rut es necesario',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es necesaria',
               // 'n_documento.unique' => 'El numero de documento ya existe en tus registros',
                'fecha_ingreso.required' => 'La fecha de ingreso es necesaria',
                'nombres.required' => 'El nombre es necesario',
                'a_paterno.required' => 'El apellido paterno es necesario',
                'a_materno.required' => 'El apellido materno es necesario'
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

    public function ingresar(Request $r)
    {
        $rut_limpio = $this->limpiar($r->rut);
        //dd($r->all());
    	// dd($r->file('foto'));
    	$s = new Socios;
    	// $valida_foto = $this->subir_imagen($r->file('foto'), 'storage/socios_fotos'); 

    	// if($valida_foto['estado'] == "true"){

    	// 	$s->foto = "storage/socios_fotos/".$valida_foto['nombre'];
    	// }

        $validar = $this->validar_datos_socio($r);
        $exist = Socios::where([ 'activo'=>'S','rut'=> $rut_limpio ])->get();
        //dd(count($exist)>0);
        if (count($exist)>0) {
            return ['estado'=>'failed','mensaje'=>'Este rut ya existe en la base de datos'];
        }

        if ($validar['estado'] == "success") {
        
    	
            if(!$this->valida_rut($rut_limpio)){
                return ['estado'=>'failed','mensaje'=>'Rut no valido'];
            }



        	$s->nombres = $r->nombres;
        	$s->a_paterno = $r->a_paterno;
        	$s->a_materno = $r->a_materno;
        	$s->rut = $rut_limpio;
        	$s->fecha_nacimiento = $r->fecha_nacimiento;
            $s->fecha_ingreso = $r->fecha_ingreso;
        	$s->activo = 'S';

        	if ($s->save()) {
        		return ['estado'=>'success','mensaje'=>'Socio ingresado'];
        	}

        }else{
            return ['estado'=>'failed_v', 'mensaje' => 'Error, puede que falte un dato en el formulario,o algo haya fallado, intente nuevamente'];
        }
    }

    public function listar()
    {
        return Socios::select([
            DB::raw("to_char(fecha_nacimiento, 'dd-mm-yyyy') as fecha_nacimiento_view"),
            DB::raw("to_char(fecha_ingreso, 'dd-mm-yyyy') as fecha_ingreso_view"),
            DB::raw("to_char(fecha_egreso, 'dd-mm-yyyy') as fecha_egreso_view"),
            'fecha_nacimiento',
            'fecha_ingreso',
            'fecha_egreso',

            'id',
            'rut',
            'nombres',
            'a_paterno',
            'a_materno'

        ])
        ->orderBy('fecha_ingreso','DESC')
        ->get();
    }

    public function filtrar($search='')
    {
      
        if ($search == '') {
            $this->listar();
        }
        $return = Socios::filtrar($search);
        return $return;
    }


    public function subir_imagen($get_img,$url)
    {

    	$img=$get_img;
    	$nombre=$img->getClientOriginalName();	
    	
    	if($img->move($url, $nombre)){
    		return ['estado'=>"true",'nombre'=>$nombre];
    	}
    	return "false";
    }


    public function actualizar_socio(Request $r)
    {

        $s = Socios::find($r->id);

        switch ($r->campo) {
            case 'rut':

           // dd(!$this->valida_rut($r->valor) == false);
                 if(!$this->valida_rut($r->valor)){
                    return ['estado'=>'failed','mensaje'=>'Rut no valido'];
                }

                $s->rut = $this->limpiar($r->valor);

                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Rut actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'nombres':
                 $s->nombres = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Nombre actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'a_paterno':
                 $s->a_paterno = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Apellido paterno actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'a_materno':
                 $s->a_materno = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Apellido materno actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'fecha_nacimiento':
                 $s->fecha_nacimiento = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Fecha de nacimiento actualizada!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'fecha_ingreso':
                $s->fecha_ingreso = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Fecha de ingreso actualizada!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'fecha_egreso':
                $s->fecha_egreso = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Fecha de egreso actualizada!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;
            
            default:
                # code...
                break;
        }
    }

    public function socio_por_rut($rut)
    {
        $rut_limpio = $this->limpiar($rut);

        if ($this->valida_rut($rut_limpio) == false) {
            return ['estado' => 'failed', 'mensaje' => 'El rut no es valido'];
        }
        $socio = Socios::where([
                    'rut' => $rut_limpio,
                    'activo' => 'S',    
                  ])->first();

        return $socio;
    }


    public function guardar_datos_socio(Request $r)
    {
        $insert_1 = false;
        $insert_2 = false;

        $verify_1 = false;
        $verify_2 = false;

        $verify_sdb = Socio_datos_basicos::where([
                        'activo' => 'S',
                        'socio_id' => $r->socio_id
                      ])->first();


        if (empty($verify_sdb)) {

            $sdb = new Socio_datos_basicos;
            $sdb->socio_id = $r->socio_id;
            $sdb->direccion = $r->direccion;
           // $sdb->direccion_2 = $r->direccion_2;
            $sdb->telefono = $r->telefono;
            $sdb->celular = $r->celular;
            $sdb->anexo = $r->anexo;
            $sdb->email_1 = $r->email_1;
            $sdb->email_2 = $r->email_2;
            $sdb->cargo_planta = $r->cargo_planta;
            $sdb->cargo_comision_sindicato = $r->cargo_comision_sindicato;
            $sdb->casa_propia = $casa_propia;
            $sdb->rol_turno = $r->rol_turno;
            $sdb->estado_civil_id = $r->estado_civil_id;
            $sdb->conyuge = $r->conyuge
            $sdb->activo = 'S';

            if ($sdb->save()) {
                 $insert_1 = true;
            }
        }
        else{
             $verify_1 = true;
        }
        
        $verify_ss = SocioSituacion::where([
                        'activo' => 'S',
                        'socio_id' => $socio_id
                      ])->first();


        if(empty($verify_ss)){

            $ss = new SocioSituacion;
            $ss->socio_id = $r->socio_id;
            $ss->numero_cuenta = $r->numero_cuenta;
            $ss->tipo_cuenta_banco_id = $r->tipo_cuenta_banco_id;
            $ss->banco = $r->banco;
            $ss->isapre_fonasa = $r->isapre_fonasa;
            $ss->grupo_sangre = $r->grupo_sangre;
            $ss->activo = 'S';

            if ($ss->save()) {
                $insert_2 = true;
            }

        }else{
            $verify_1 = false;
        }



        if ($insert_1 == true || $insert_2 == true) {
            return ['estado'=>'success','mensaje'=>'Informacion ingresada, en caso de que haya faltado algo, puede insertarlo desde la tabla. '];
        }
        else{
            return ['estado'=>'failed','mensaje'=>'No se han ingresado datos, intente nuevamente.'];
        }

    }






    public function actualizar_datos_socio(Request $r)//$id, $campo, $valor
    {
        $sdb = Socio_datos_basicos::where(['activo' => 'S','socio_id' => $r->id])->first();
        $ss = SocioSituacion::where(['activo' => 'S','socio_id' => $r->id])->first();

        switch ($r->campo) {
            case 'socio_direccion':

                    $sdb->direccion = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Direccion actualizada!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_telefono':

                    $sdb->telefono = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Telefono actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_celular':
                    
                     $sdb->celular = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Celular actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_anexo':
                
                    $sdb->anexo = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Anexo actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_email_1':

                    $sdb->email_1 = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Email personal actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_email_2':

                    $sdb->email_2 = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Email corporativo actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_cargo_planta':
                    
                    $sdb->cargo_planta = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Cargo de planta actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_cargo_comision_sindicato':
                    
                    $sdb->cargo_comision_sindicato = $r->valor;
                    if ($sdb->save()) { 
                        return ['estado'=>'success','mensaje'=>'Cargo o comision de planta actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_numero_cuenta':
                    
                    $ss->numero_cuenta = $r->valor;
                    if ($ss->save()) { 
                        return ['estado'=>'success','mensaje'=>'Numero de cuenta actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;
            case 'socio_tipo_cuenta_banco_id':

                    $ss->tipo_cuenta_banco_id = $r->valor;
                    if ($ss->save()) { 
                        return ['estado'=>'success','mensaje'=>'Tipo de cuenta actualizada!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;
            case 'socio_banco':

                    $ss->banco = $r->valor;
                    if ($ss->save()) { 
                        return ['estado'=>'success','mensaje'=>'Nombre de banco actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;
            case 'socio_isapre_fonasa':

                    $ss->isapre_fonasa = $r->valor;
                    if ($ss->save()) { 
                        return ['estado'=>'success','mensaje'=>'Isapre o fonaza actualizada!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
            case 'socio_grupo_sangre':
                    
                    $ss->grupo_sangre = $r->valor;
                    if ($ss->save()) { 
                        return ['estado'=>'success','mensaje'=>'Grupo de sangre actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }

            break;
           
            
            default:
                # code...
                break;
        }
    }

    function valida_rut($rut)
    {
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
