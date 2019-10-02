<?php

namespace App\Http\Controllers;

use App\User;
use App\Socios;
use App\SocioCarga;
use App\SocioConyuge;
use App\CbeNacimiento;
use App\SocioSituacion;
use App\CuentaConsorcio;
use App\CbeFallecimiento;
use App\CbeGastosMedicos;
use App\SocioBeneficiario;
use App\SocioPadresSuegros;
use App\Socio_datos_basicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
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
        ->orderBy('a_paterno','ASC')
        ->orderBy('a_materno','ASC')

        ->get();
    }

    public function filtrar($search='')
    {
      
        if ($search == '') {
            $this->listar();
        }
        $return = Socios::filtrar($search);
        return Response()->json($return);
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

                    $desvincular = $this->desvincular_socio_consorcio($s);

                    if ($desvincular) {
                        return ['estado'=>'success','mensaje' => 'Fecha de egreso actualizada, socio desvinculado!' ];
                    }
                    return ['estado'=>'failed','mensaje' => 'Fecha de egreso actualizada' ];

                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;
            
            default:
                # code...
                break;
        }
    }

    function desvincular_socio_consorcio($s){

        $f = $this->div_fecha($s->fecha_egreso);
        $anio = $this->anio_tipo_id($f['anio']);

        $cc = CuentaConsorcio::where(['anio_id' => $anio->id,'socio_id' => $s->id])->first();

        if ($cc) {

            $cc->vinculado = 'N'; //aqui el socio se desvincula de consorcio en el año indicado

            if ($cc->save()) {
                return true;
            }
            return false;
        }
        return false;

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
        if ($socio) {
            return $socio;
        }
        return ['estado'=>'failed','mensaje'=>'No hay datos encontrados'];

        
    }

//--DATOS SOCIO---------------------------------------------------------
    public function guardar_datos_socio(Request $r)
    {
        try{
            if ($r->email_1 == '' || $r->direccion == '') {
                return ['estado'=>'failed','mensaje'=>'La direccion y el email personal son obligatorios'];
            }


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
                $sdb->casa_propia = $r->casa_propia;
                $sdb->rol_turno = $r->rol_turno;
                $sdb->estado_civil_id = $r->estado_civil_id;
                $sdb->conyuge = $r->conyuge;
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
                            'socio_id' => $r->socio_id
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
                return ['estado'=>'failed','mensaje'=>'No se han ingresado datos, posiblemente ya existan algunos datos aqui, visualice la tabla o intente nuevamente.'];
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
    public function actualizar_datos_socio(Request $r)//$id, $campo, $valor
    {
        if (empty($r->valor)) {
            return ['estado'=>'failed', 'mensaje'=>'No hay un valor ingresado'];
        }

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
                        return ['estado'=>'success','mensaje'=>'Isapre o fonasa actualizada!']; 
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
    //             // -----------------------------------------
            case 'socio_casa_propia':

                    $sdb->casa_propia = $r->valor;
                    if ($sdb->save()) { 

                        return ['estado'=>'success','mensaje'=>'Estado casa propia actualizada!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;

            case 'socio_rol_turno':

                    $sdb->rol_turno = $r->valor;
                    if ($sdb->save()) { 

                        return ['estado'=>'success','mensaje'=>'Rol de turno actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;

            case 'socio_estado_civil_id':

                    $sdb->estado_civil_id = $r->valor;
                    if ($sdb->save()) { 

                        return ['estado'=>'success','mensaje'=>'Estado civil actualizado!']; 

                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;


            case 'socio_conyuge':

                    $sdb->conyuge = $r->valor;
                    if ($sdb->save()) { 

                        return ['estado'=>'success','mensaje'=>'Conyuge actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;

            // case 'archivo':
               

            // break;

           
         }
    }
    public function traer_datos_socio($socio_id)
    {
        $list = DB::select("SELECT * from socios_datos_basicos as sdb
                    inner join socio_situacion ss on ss.socio_id = sdb.socio_id where sdb.socio_id = $socio_id 
                    and sdb.activo = 'S' and ss.activo = 'S'");

        if (count($list) > 0) {
            return $list;
        }
        return ['estado'=>'failed','mensaje'=>'No hay datos en la tabla'];
    }
//--fin---DATOS SOCIO---------------------------------------------------------


//--DATOS DE LA CONYUGE-------------------------------------------------------    
    public function guardar_datos_conyuge(Request $r)
    {
        try{
            $validar = $this->validar_datos_conyuge($r);

            if ($validar['estado'] == "success") {
                    
                    $rut_limpio = $this->limpiar($r->rut);

                    if ($this->valida_rut($rut_limpio) == false ) {
                        return ['estado' => 'failed','mensaje'=>'Rut no valido'];
                    }

                    $file = $this->guardarArchivo($r->archivo,'ArchivosSocios/ArchivosConyuge/');

                    if($file['estado'] == "success"){
                        $archivo = 'storage/' . $file['archivo'];
                    }else{
                        return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
                    }


                    $verify_conyuge = SocioConyuge::where([
                                            'activo' => 'S',
                                            'socio_id' => $r->socio_id
                                      ])->first();


                    if (empty($verify_conyuge)) {
                            $cony = new SocioConyuge;
                            $cony->socio_id = $r->socio_id;
                            $cony->rut = $rut_limpio;
                            $cony->fecha_nacimiento = $r->fecha_nacimiento;
                            $cony->nombres = $r->nombres;
                            $cony->apellido_paterno = $r->apellido_paterno;
                            $cony->apellido_materno = $r->apellido_materno;
                            $cony->direccion = $r->direccion;
                            $cony->celular = $r->celular;
                            $cony->activo = 'S';
                            $cony->archivo = $archivo;
                            if ($cony->save()) {
                                return ['estado'=>'success', 'mensaje'=>'Datos de conyuge ingresados, para actualizar datos abra la tabla conyuge/pareja'];
                            }else{
                                return ['estado'=>'failed','mensaje'=>'No es posible almacenar algunos datos'];
                            }
                    }else{
                        return ['estado'=>'failed','mensaje'=>'No es posible volver a ingresar un(a) conyuge/pareja'];
                    }
            }else{
                return ['estado'=>'failed','mensaje'=>'Posiblemente falten datos en el formulario'];
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
    public function traer_datos_conyuge($socio_id)
    {
        $conyuge = SocioConyuge::where([
                        'activo'=>'S',
                        'socio_id' => $socio_id
                    ])->first();
        //return $conyuge;
        if ($conyuge) {
            return ['estado'=>'success', 'body'=>$conyuge];
        }
        return ['estado'=>'failed', 'body'=>'', 'mensaje'=>'No hay datos en esta tabla'];
    }
    public function actualizar_datos_conyuge(Request $r)
    {
        if (empty($r->valor)) {
            return ['estado'=>'failed', 'mensaje'=>'No hay un valor ingresado'];
        }

        $cony = SocioConyuge::where(['activo'=>'S', 'socio_id'=>$r->id])->first();

        switch ($r->campo) {
            case 'direccion':
                $cony->direccion = $r->valor;
                if ($cony->save()) {
                    return ['estado'=>'success', 'mensaje'=>'Direccion actualizada!'];
                }else{
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                }
            break;
            case 'celular':
                 $cony->celular = $r->valor;
                 if ($cony->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Celular actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'rut':

                $rut_limpio = $this->limpiar($r->valor);

                $existe_conyuge = SocioConyuge::where([
                                            'activo'=>'S', 
                                            'socio_id'=>$r->id,
                                            'rut' => $rut_limpio
                                    ])->first();
                // dd(!$this->valida_rut($rut_limpio));
                if(!$this->valida_rut($rut_limpio)){

                    return ['estado'=>'failed','mensaje'=>'Rut no valido'];
                }
                if (!empty($existe_conyuge)) {
                    return ['estado'=>'failed','mensaje'=>'Rut ya asociado a este socio'];
                }

                $cony->rut = $rut_limpio;
                 if ($cony->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Rut actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'fecha_nacimiento':

                $cony->fecha_nacimiento = $r->valor;
                 if ($cony->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Fecha de nacimiento actualizada!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'nombres':

                $cony->nombres = $r->valor;
                 if ($cony->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Nombre actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'apellido_paterno':

                $cony->apellido_paterno = $r->valor;
                 if ($cony->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Apellido paterno actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'apellido_materno':

                $cony->apellido_materno = $r->valor;
                 if ($cony->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Apellido materno actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'archivo':
                 //actualizar----------------------------------------------------------------------------
                     $valida_pdf = $this->validar_pdf_upd($r);
                        if($valida_pdf['estado'] == 'success'){
                         $ruta = substr($cony->archivo,8);
                            
                            $borrar = Storage::delete('/'.$ruta);
                            if ($borrar) {
                                $guardarArchivo = $this->guardarArchivo($r->valor, 'ArchivosSocios/ArchivosConyuge/');

                                if ($guardarArchivo['estado'] == "success") {
                                    $cony->archivo = 'storage/' . $guardarArchivo['archivo'];
                                    if ($cony->save()) {
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

        
                //fin actualzar-------------------------------------------------------------------
            break;

            default:
                # code...
            break;
        }
    }
//--------------------------------------------------------------------------------    



//--INICIO-- DATOS DEL BENEFICIARIO--------------------------------------------------
    public function guardar_datos_beneficiario(Request $r)
    {   
        try{

            if (empty($r->prioritario)) {
                return ['estado'=>'failed','mensaje'=>'No ha seleccionado una prioridad'];
            }

            $rut_limpio = $this->limpiar($r->rut);

            $validar_rut = $this->valida_rut($rut_limpio);

            if (!$validar_rut) {
                return ['estado'=>'failed', 'mensaje'=>'Rut no valido o inexistente'];
            }

            $existe_prioridad = SocioBeneficiario::where([
                            'activo'  => 'S',
                            'socio_id'=> $r->socio_id,
                            'prioritario' => $r->prioritario
                        ])->first();
            
            if ($existe_prioridad && $r->prioritario != 10 ) {
                return ['estado'=>'failed', 'mensaje'=>'Prioridad ya en uso'];
            }


            $existe_beneficiario = SocioBeneficiario::where([
                            'activo'  => 'S',
                            'socio_id'=> $r->socio_id,
                            'rut'     => $r->rut_limpio,
                            'cobro_beneficio' => 'N'
                        ])->first();
            $existe_cobro_de_rut_en_socio = SocioBeneficiario::where([
                            'activo'   =>'S',
                            'socio_id' => $r->id,
                            'rut'      => $rut_limpio,
                            'cobro_beneficio' => 'S'
                        ])->first();

            if (!empty($existe_beneficiario)) {
                return ['estado'=>'failed','mensaje'=>'Este rut ya esta como beneficiario para este socio'];
            }

                       
            if (!empty($existe_cobro_de_rut_en_socio)) {
                return [
                    'estado'  => 'failed',
                    'mensaje' => 'Este rut ya tiene un beneficio para este socio'
                ];
            }

            $sb = new SocioBeneficiario;
            $sb->socio_id = $r->socio_id;
            $sb->relacion = $r->relacion;
            $sb->rut = $r->rut;
            $sb->fecha_nacimiento = $r->fecha_nacimiento;
            $sb->nombres = $r->nombres;
            $sb->apellido_paterno = $r->apellido_paterno;
            $sb->apellido_materno = $r->apellido_materno;
            $sb->direccion = $r->direccion;
            $sb->celular = $r->celular;
            $sb->activo = 'S';
            $sb->cobro_beneficio = 'N';
            $sb->prioritario = $r->prioritario;
            if ($sb->save()) {
                return ['estado'=>'success','mensaje'=>'Beneficiario ingresado con exito!'];
            }
            return ['estado'=>'failed','mensaje'=>'No se pudo ingresar el beneficiario!'];

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
    public function traer_datos_beneficiario($socio_id)
    {
        $beneficiario = SocioBeneficiario::where([
                        'activo'=>'S',
                        'socio_id' => $socio_id
                    ])
                    ->orderBy('prioritario','ASC')
                    ->get();
       
        if (count($beneficiario) > 0) {
            return ['estado'=>'success', 'body'=>$beneficiario];
        }
        return ['estado'=>'failed', 'body'=>'', 'mensaje'=>'No hay datos en la tabla'];
    }
    public function actualizar_datos_beneficiario(Request $r)
    {//id(socio),campo,valor y socio_beneficiario_id

         // dd($r->all());
        if (empty($r->valor)) {
            return ['estado'=>'failed', 'mensaje'=>'No hay un valor ingresado'];
        }

        $ben = SocioBeneficiario::where([
                    'activo'=>'S',
                    'socio_id' => $r->id,
                    'id' => $r->socio_beneficiario_id,
                    'cobro_beneficio' => 'N'
               ])->first();

     if (empty($ben)) {
         return ['estado'=>'failed','mensaje'=>'No es posible cambiar algun dato, esta persona cuenta con un beneficio o no esta apta por el momento'];
     }

        if ($ben) {
            switch ($r->campo) {
                case 'relacion':
                    $ben->relacion = $r->valor;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Relacion actualizada!'];
                    }
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                case 'rut':

                    $rut_limpio = $this->limpiar($r->valor);

                    if(!$this->valida_rut($rut_limpio)){
                        return ['estado'=>'failed','mensaje'=>'Rut no valido'];
                    }

                    $existe_rut_en_socio = SocioBeneficiario::where([
                        'activo'=>'S',
                        'socio_id' => $r->id,
                        'rut' => $rut_limpio,
                        'cobro_beneficio'=>'N'
                    ])->first();

                    $existe_cobro_de_rut_en_socio = SocioBeneficiario::where([
                        'activo'=>'S',
                        'socio_id' => $r->id,
                        'rut' => $rut_limpio,
                        'cobro_beneficio' => 'S'
                    ])->first();

                    if (!empty($existe_rut_en_socio)) {
                        return [
                            'estado'  => 'failed',
                            'mensaje' => 'Ya existe este rut para este socio'
                        ];
                    }
                    if (!empty($existe_cobro_de_rut_en_socio)) {
                        return [
                            'estado'  => 'failed',
                            'mensaje' => 'Este rut ya tiene un beneficio para este socio'
                        ];
                    }


                    $ben->rut = $rut_limpio;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Rut actualizado!'];
                    }
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                case 'fecha_nacimiento':
                    $ben->fecha_nacimiento = $r->valor;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Fecha de nacimiento actualizada!'];
                    }
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                case 'nombres':
                    $ben->nombres = $r->valor;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Nombre actualizado!'];
                    }
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                case 'apellido_paterno':
                    $ben->apellido_paterno = $r->valor;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Apellido paterno actualizado!'];
                    }
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                case 'apellido_materno':
                    $ben->apellido_materno = $r->valor;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Apellido materno actualizado!'];
                    }
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                case 'direccion':
                    $ben->direccion = $r->valor;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Dirección actualizada!'];
                    }
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                 case 'celular':
                    $ben->celular = $r->valor;
                    if ($ben->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Celular actualizado!'];
                    }
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                break;
                
                default:
                    # code...
                break;
            }
        }
    }
//-----------------------------------------------------------------------------------

// -- INICIO -- DATOS DE LA CARGA--------------------------------------------------

    public function guardar_datos_carga(Request $r)
    {
        try{
            $validar = $this->validar_datos_carga($r);

            if ($r->tipo_carga_id == '5') { // si es conyuge el tipo
                
                $ca = SocioConyuge::where([
                                'socio_id' => $r->socio_id,
                ])->first();
                
                if ($ca) {
                    $rut_limpio = $this->limpiar($r->rut);
                        
                    if (strtolower($ca->rut) != strtolower($rut_limpio)) {
                        return ['estado' => 'failed', 'mensaje'=> 'El rut de la conyugue no coincide con el socio'];
                    }
                }

                $con = SocioCarga::where([
                                'socio_id' => $r->socio_id,
                                'tipo_carga_id' =>'5'
                ])->first();

                if ($con) {
                    return ['estado' => 'failed', 'mensaje'=> 'Este socio ya tiene una conyuge como carga'];
                }
            
        
            }
            // SI NO SIGUE SU PROCESO NORMAL


            if ($validar['estado'] == 'success') {
                        
                $existe_carga = SocioCarga::where(['activo'=>'S','rut'=>$r->rut,'socio_id'=>$r->socio_id])->first();

                if (!empty($existe_carga)) {
                    return ['estado'=>'failed','mensaje'=>'Este rut ya esta como carga para este socio'];
                }
                $rut_limpio = $this->limpiar($r->rut);
                if(!$this->valida_rut($rut_limpio)){

                    return ['estado'=>'failed','mensaje'=>'Rut no valido'];
                }

                $file = $this->guardarArchivo($r->archivo,'ArchivosSocios/ArchivosCargas/');

                if($file['estado'] == "success"){
                    $archivo = 'storage/' . $file['archivo'];
                }else{
                    return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
                }

                $carga = new SocioCarga;
                $carga->socio_id = $r->socio_id;
                $carga->tipo_carga_id = $r->tipo_carga_id;
                $carga->rut = $rut_limpio;
                $carga->fecha_nacimiento = $r->fecha_nacimiento;
                $carga->nombres = $r->nombres;
                $carga->apellido_paterno = $r->apellido_paterno;
                $carga->apellido_materno = $r->apellido_materno;
                $carga->direccion = $r->direccion;
                $carga->celular = $r->celular;
                $carga->establecimiento = $r->establecimiento;
                $carga->archivo = $archivo;
                $carga->activo = 'S';

                if ($carga->save()) {

                    return ['estado'=>'success','mensaje'=>'La carga se ha ingresado correctamente'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al ingresas carga'];
            }else{
                return ['estado'=>'failed','mensaje'=>'Posiblemente falten campos por llenar'];
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
    public function traer_datos_carga($socio_id)
    {
        $carga = SocioCarga::where(['activo'=>'S','socio_id'=>$socio_id])
                 ->orderBy('created_at','ASC')
                 ->get();

        if (count($carga)>0) {
            return ['estado'=>'success', 'mensaje'=>'si hay datos','body'=>$carga];
        }
        return ['estado'=>'failed','body'=>'','mensaje'=>'No hay datos en la tabla'];
    }
    public function actualizar_datos_carga(Request $r)
    {// id(socio),campo,valor y carga_socio_id

        if (empty($r->valor)) {
            return['estado'=>'failed', 'mensaje'=>'No hay dato para actualizar!'];
        }

        $carga = SocioCarga::where([
                        'activo' => 'S',
                        'socio_id' => $r->id,
                        'id' => $r->carga_socio_id
                ])->first();

        if(empty($carga)){
            return ['estado'=>'failed','mensaje'=>'No se han encontrado datos para actualizar'];
        }

        switch ($r->campo) {
            case 'tipo_carga_id':
                $carga->tipo_carga_id = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success', 'mensaje'=>'Relación actualizada!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;
            case 'rut':
                $rut_limpio = $this->limpiar($r->valor);

                $existe_rut = SocioCarga::where([
                                        'activo'=>'S', 
                                        'socio_id'=>$r->id,
                                        'rut' => $rut_limpio
                              ])->first();

                if(!$this->valida_rut($rut_limpio)){

                    return ['estado'=>'failed','mensaje'=>'Rut no valido'];
                }
                if (!empty($existe_rut)) {
                    return ['estado'=>'failed','mensaje'=>'Rut ya asociado a este socio'];
                }

                $carga->rut = $rut_limpio;
                 if ($carga->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Rut actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;
            case 'fecha_nacimiento':
                $carga->fecha_nacimiento = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success','mensaje'=>'Fecha de nacimiento actualizado!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;
            case 'nombres':
                $carga->nombres = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success','mensaje'=>'Nombre actualizado!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;
            case 'apellido_paterno':
                $carga->apellido_paterno = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success','mensaje'=>'Apellido paterno actualizado!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;
            case 'apellido_materno':
                $carga->apellido_materno = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success','mensaje'=>'Apellido materno actualizado!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;
            case 'direccion':
                $carga->direccion = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success','mensaje'=>'Dirección actualizada!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;
            case 'celular':
                $carga->celular = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success','mensaje'=>'Celular actualizado!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;
            case 'establecimiento':
                $carga->establecimiento = $r->valor;
                if ($carga->save()) {
                    return ['estado'=>'success','mensaje'=>'Establecimiento actualizado!'];
                }
                return ['estado'=>'failed','mensaje'=>'Error al actualizar!'];
            break;

             case 'archivo':
                 //actualizar----------------------------------------------------------------------------
                     $valida_pdf = $this->validar_pdf_upd($r);
                        if($valida_pdf['estado'] == 'success'){
                         $ruta = substr($carga->archivo,8);
                            
                            $borrar = Storage::delete('/'.$ruta);
                            if ($borrar) {
                                $guardarArchivo = $this->guardarArchivo($r->valor, 'ArchivosSocios/ArchivosCargas/');

                                if ($guardarArchivo['estado'] == "success") {
                                    $carga->archivo = 'storage/' . $guardarArchivo['archivo'];
                                    if ($carga->save()) {
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

        
                //fin actualzar-------------------------------------------------------------------
            break;
            
            default:
                # code...
                break;
        }
    }

// -------------------------------------------------------------------------------

// -- INICIO DATOS  PADRES Y SUEGROS

    public function guardar_datos_padres_suegros(Request $r)
    {
        try{
            $rut_limpio = $this->limpiar($r->rut);

            $verify_i = SocioPadresSuegros::where(['rut'=> $rut_limpio ,'activo'=>'S','socio_id'=>$r->socio_id])->first();
            if(!$this->valida_rut($rut_limpio)){

                return ['estado'=>'failed','mensaje'=>'Rut no valido'];
            }

            if (!empty($verify_i)) {
                return ['estado'=>'failed','mensaje'=>'Este rut ya esta en datos del padre y/o suegros'];
            }else{

                $i = new SocioPadresSuegros;
                $i->socio_id = $r->socio_id;
                $i->relacion_socio_id = $r->relacion_socio_id;
                $i->rut = $rut_limpio;
                $i->fecha_nacimiento = $r->fecha_nacimiento;
                $i->nombres = $r->nombres;
                $i->apellido_paterno = $r->apellido_paterno;
                $i->apellido_materno = $r->apellido_materno;
                $i->direccion = $r->direccion;
                $i->celular = $r->celular;
                $i->activo = 'S';
                if ($i->save()) {
                    return [ 'estado'=>'success', 'mensaje'=>'Persona ingresada con exito!' ];
                }else{
                    return [ 'estado'=>'success', 'mensaje'=>'Persona ingresada con exito!' ];
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
    public function traer_datos_padres_suegros($socio_id)
    {
        $listar = SocioPadresSuegros::where(['activo'=>'S','socio_id'=>$socio_id])
                   ->orderBy('relacion_socio_id','ASC')
                  ->get();
        if (count($listar) > 0) {
            return [
                'estado'  => 'success',
                'mensaje' => 'success',
                'body'    => $listar
            ];
        }
        else{
            return [
                'estado'  => 'failed',
                'mensaje' => 'No hay datos en la tabla',
                'body'    => null
            ];
        }
    }
    public function actualizar_datos_padres_suegros(Request $r)
    { // id(socio),campo,valor y socio_padre_id
        if (empty($r->valor)) {
            return ['estado'=>'failed', 'mensaje'=>'No hay un valor ingresado'];
        }

        $pys = SocioPadresSuegros::where([
            //'rut'=> $rut_limpio ,
            'activo'=>'S',
            'socio_id'=>$r->id,
            'id' => $r->socio_padre_suegro_id
        ])->first();

        if(empty($pys)){
            return ['estado'=>'failed','mensaje'=>'No se han encontrado datos para actualizar'];
        }
        
        switch ($r->campo) {
            case 'relacion_socio_id':
                $pys->relacion_socio_id = $r->valor;
                if ($pys->save()) {
                    return ['estado'=>'success', 'mensaje'=>'Relación actualizada!'];
                }else{
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                }
            break;
            case 'direccion':
                $pys->direccion = $r->valor;
                if ($pys->save()) {
                    return ['estado'=>'success', 'mensaje'=>'Direccion actualizada!'];
                }else{
                    return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                }
            break;
            case 'celular':
                 $pys->celular = $r->valor;
                 if ($pys->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Celular actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'rut':

                $rut_limpio = $this->limpiar($r->valor);

                $existe_rut = SocioPadresSuegros::where([
                                            'activo'=>'S', 
                                            'socio_id'=>$r->id,
                                            'rut' => $rut_limpio
                                    ])->first();

                if(!$this->valida_rut($rut_limpio)){

                    return ['estado'=>'failed','mensaje'=>'Rut no valido'];
                }
                if (!empty($existe_rut)) {
                    return ['estado'=>'failed','mensaje'=>'Rut ya asociado a este socio'];
                }

                $pys->rut = $rut_limpio;
                 if ($pys->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Rut actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'fecha_nacimiento':

                $pys->fecha_nacimiento = $r->valor;
                 if ($pys->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Fecha de nacimiento actualizada!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'nombres':

                $pys->nombres = $r->valor;
                 if ($pys->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Nombre actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'apellido_paterno':

                $pys->apellido_paterno = $r->valor;
                 if ($pys->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Apellido paterno actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;

            case 'apellido_materno':

                $pys->apellido_materno = $r->valor;
                 if ($pys->save()) {
                     return ['estado'=>'success', 'mensaje'=>'Apellido materno actualizado!'];
                 }else{
                     return ['estado'=>'success', 'mensaje'=>'Error al actualizar!'];
                 }
            break;


            default:
                # code...
            break;
        }
    }


// -- FIN DATOS PADRES Y SUEGROS    
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

    public function verificar_beneficio_por_rut($rut, $socio_id)
    {
        return false;
    }

    function validar_datos_carga($request)
    {
        $validator = Validator::make($request->all(), 
            [   
                'socio_id' => 'required',
                'tipo_carga_id' => 'required',
                'rut' => 'required',
                'fecha_nacimiento' => 'required|date',
                'nombres' => 'required|min:0',
                'apellido_paterno' => 'required',
                'apellido_materno' => 'required',
                'direccion' => 'required',
                'celular' => 'required',
                'archivo' => 'required|mimes:doc,pdf,docx',
            ],
            [
                'socio_id.required' => 'El socio es necesario',
                'tipo_carga_id.required' => 'el tipo de carga es necesario',
                'rut.required' => 'El rut es necesario',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es necesaria',
                'nombres.required' => 'El nombre es necesario',
                'apellido_paterno.required' => 'El apellido paterno es necesario',
                'apellido_materno.required' => 'El apellido materno es necesario',
                'direccion.required' => 'La direccion es necesaria',
                'celular.required' => 'el celular es necesario',
                'archivo.required' => 'el archivo es requerido',
                'archivo.mimes' => 'el archivo no es un PDF'
            ]);

 
            if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
            return ['estado' => 'success', 'mensaje' => 'success'];
    }

    function validar_datos_conyuge($request)
    {
      
        $validator = Validator::make($request->all(), 
            [   
                'socio_id' => 'required',
                'rut' => 'required',
                'fecha_nacimiento' => 'required|date',
                'nombres' => 'required|min:0',
                'apellido_paterno' => 'required',
                'apellido_materno' => 'required',
                'direccion' => 'required',
                'celular' => 'required',
                'archivo' => 'required|mimes:pdf',
            ],
            [
                'socio_id.required' => 'El socio es necesario',
                'rut.required' => 'El rut es necesario',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es necesaria',
                'nombres.required' => 'El nombre es necesario',
                'apellido_paterno.required' => 'El apellido paterno es necesario',
                'apellido_materno.required' => 'El apellido materno es necesario',
                'direccion.required' => 'La direccion es necesaria',
                'celular.required' => 'el celular es necesario',
                'archivo.required' => 'El documento no se ha subido',
                'archivo.mimes' => 'Debe ser formato PDF'
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

    public function subir_archivo_general_socio(Request $r)
    {
        //actualizar----------------------------------------------------------------------------
        $up_sdb = Socio_datos_basicos::where([
            'activo'=>'S',
            'socio_id'=>$r->id,
        ])
        ->whereNotNull('archivo')
        ->first();


        if (!empty($up_sdb)) {
            
             $valida_pdf = $this->validar_pdf($r);
                if($valida_pdf['estado'] == 'success'){
                 $ruta = substr($up_sdb->archivo,8);
                    
                    $borrar = Storage::delete('/'.$ruta);
                    if ($borrar) {
                        $guardarArchivo = $this->guardarArchivo($r->archivo, 'ArchivosSocios/DatosBasicos/');

                        if ($guardarArchivo['estado'] == "success") {
                            $up_sdb->archivo = 'storage/'.$guardarArchivo['archivo'];
                            if ($up_sdb->save()) {
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

        }
        //fin actualzar-------------------------------------------------------------------

        //insertar----------------------------------------------------------------------------
        $sdb = Socio_datos_basicos::where(['activo'=>'S','socio_id'=>$r->id])->first();

        if (!empty($sdb)) {
            $file = $this->guardarArchivo($r->archivo,'ArchivosSocios/DatosBasicos/');

            if($file['estado'] == "success"){
                $archivo = $file['archivo'];

                $sdb->archivo = 'storage/'. $archivo;
                if ($sdb->save()) {
                    return ['estado'=>'success', 'mensaje'=>'Documento subido correctamente!'];
                }
                return ['estado'=>'failed', 'mensaje'=>'Error al guardar documento!'];
            }else{
                return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
            }
        }
        else{
             return ['estado'=>'failed','mensaje'=>'No se han ingresado datos basicos para este socio'];
        }

    }

    public function estado_de_socio_en_portal_beneficio($socio_id)
    {
        $socio = Socios::where(['activo'=>'S','id'=>$socio_id])->first();
        if (!empty($socio)) {
            $user = User::where(['rut'=>$socio->rut])->first();
            if (!empty($user)) {
                return ['estado'=>'Portal socio asignado'];
            }
            return ['estado'=>'No asignado'];
        }
        return ['estado'=>'No asignado'];
    }

    public function validar_pdf($request)
    {
        $val = Validator::make($request->all(), 
            [

                'archivo' => 'required|mimes:pdf',
            ],
            [
                'input.required' => 'El PDF es necesario',
                'input.mimes' => 'El archivo no es PDF',
            ]);

 
            if ($val->fails()){ return ['estado' => 'failed_v', 'mensaje' => $val->errors()];}
            return ['estado' => 'success', 'mensaje' => 'success'];
    }

    public function validar_pdf_upd($request)
    {
        $val = Validator::make($request->all(), 
            [

                'valor' => 'required|mimes:pdf',
            ],
            [
                'input.required' => 'El PDF es necesario',
                'input.mimes' => 'El archivo no es PDF',
            ]);

 
            if ($val->fails()){ return ['estado' => 'failed_v', 'mensaje' => $val->errors()];}
            return ['estado' => 'success', 'mensaje' => 'success'];
    }

    public function listar_beneficios_cobrados($socio_id)
    {   
        try{
                $nac = CbeNacimiento::mis_beneficios($socio_id);
                $fall = CbeFallecimiento::mis_beneficios($socio_id);
                $gm = CbeGastosMedicos::mis_beneficios($socio_id);

                //excepcion con fallecimientos; dibujar un arreglo
                foreach ($nac as $n) {
                    $persona = Socios::nombre_por_rut($n->rut, $socio_id);
                    $n->nombre = $persona;
                }
    // 225698724
                foreach ($fall as $f) {
                    $persona = Socios::nombre_por_rut($f->rut_fallecido, $socio_id);
                    $f->nombre = $persona;
                }

                return [
                    'nacimiento' => $nac,
                    'fallecimiento' => $fall,
                    'gastos_medicos' => $gm
                ];
        }catch(QueryException $e){
            return[
                'estado'  => 'failed', 
                'mensaje' => 'QEx:Error, posiblemente no existen beneficios cobrados, intente nuevamente o verifique sus datos'
            ];
        }
        catch(\Exception $e){
            return[
                'estado'  => 'failed', 
                'mensaje' => 'Ex: Error, posiblemente no existen beneficios cobrados, intente nuevamente o verifique sus datos'
            ];
        }
    }

    public function anio_tipo_id($value)
    {

    	$data = DB::table('anio')->where('descripcion', $value)->first();

    	if(!empty($data)){

    	 	return $data;
    	} 
    		
    }


}
