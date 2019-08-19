<?php

namespace App\Http\Controllers;

use App\SocioBeneficiario;
use App\SocioCarga;
use App\SocioConyuge;
use App\SocioPadresSuegros;
use App\SocioSituacion;
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

//--DATOS SOCIO---------------------------------------------------------
    public function guardar_datos_socio(Request $r)
    {

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
    //             // -----------------------------------------
            case 'socio_casa_propia':


                    // $sdb->casa_propia = $r->valor;
                    // if ($sdb->save()) { 

                    //     return ['estado'=>'success','mensaje'=>'Estado casa propia actualizada!']; 

                    // $sdb->casa_propia = $r->valor;
                    // if ($sdb->save()) { 

                    $sdb->casa_propia = $r->valor;
                    if ($sdb->save()) { 

                        return ['estado'=>'success','mensaje'=>'Estado casa propia actualizada!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;

            case 'socio_rol_turno':
                    

                    // $sdb->rol_turno = $r->valor;
                    // if ($sdb->save()) { 

                    $sdb->rol_turno = $r->valor;
                    if ($sdb->save()) { 

                        return ['estado'=>'success','mensaje'=>'Rol de turno actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;

            case 'socio_estado_civil_id':
                    

                    // $sdb->estado_civil_id = $r->valor;
                    // if ($sdb->save()) { 

                    $sdb->estado_civil_id = $r->valor;
                    if ($sdb->save()) { 

                        return ['estado'=>'success','mensaje'=>'Estado civil actualizado!']; 

                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;


            case 'socio_conyuge':
                    

                    // $sdb->conyuge = $r->valor;
                    // if ($sdb->save()) { 

                    $sdb->conyuge = $r->valor;
                    if ($ss->save()) { 

                        return ['estado'=>'success','mensaje'=>'Conyuge actualizado!']; 
                    }
                    else {
                        return ['estado'=>'failed','mensaje'=>'Error al actualizar!']; 
                    }
            break;

           
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
        $validar = $this->validar_datos_conyuge($r);

        if ($validar['estado'] == "success") {
                
                $rut_limpio = $this->limpiar($r->rut);

                if ($this->valida_rut($rut_limpio) == false ) {
                    return ['estado' => 'failed','mensaje'=>'Rut no valido'];
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
        return ['estado'=>'failed', 'body'=>'', 'mensaje'=>'No existen datos en esta tabla'];
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


            default:
                # code...
            break;
        }
    }
//--------------------------------------------------------------------------------    



//--INICIO-- DATOS DEL BENEFICIARIO--------------------------------------------------
    public function guardar_datos_beneficiario(Request $r)
    {   $rut_limpio = $this->limpiar($r->rut);
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
        if ($sb->save()) {
            return ['estado'=>'success','mensaje'=>'Beneficiario ingresado con exito!'];
        }
        return ['estado'=>'failed','mensaje'=>'No se pudo ingresar el beneficiario!'];
    }
    public function traer_datos_beneficiario($socio_id)
    {
        $beneficiario = SocioBeneficiario::where([
                        'activo'=>'S',
                        'socio_id' => $socio_id
                    ])->get();
       
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

        $validar = $this->validar_datos_carga($r);


        if ($validar['estado'] == 'success') {
                    
            $existe_carga = SocioCarga::where(['activo'=>'S','rut'=>$r->rut,'socio_id'=>$r->socio_id])->first();

            if (!empty($existe_carga)) {
                return ['estado'=>'failed','mensaje'=>'Este rut ya esta como carga para este socio'];
            }

            $carga = new SocioCarga;
            $carga->socio_id = $r->socio_id;
            $carga->tipo_carga_id = $r->tipo_carga_id;
            $carga->rut = $r->rut;
            $carga->fecha_nacimiento = $r->fecha_nacimiento;
            $carga->nombres = $r->nombres;
            $carga->apellido_paterno = $r->apellido_paterno;
            $carga->apellido_materno = $r->apellido_materno;
            $carga->direccion = $r->direccion;
            $carga->celular = $r->celular;
            $carga->establecimiento = $r->establecimiento;
            $carga->activo = 'S';

            if ($carga->save()) {

                return ['estado'=>'success','mensaje'=>'La carga se ha ingresado correctamente'];
            }
            return ['estado'=>'failed','mensaje'=>'Error al ingresas carga'];
        }
    }
    public function traer_datos_carga($socio_id)
    {
        $carga = SocioCarga::where(['activo'=>'S','socio_id'=>$socio_id])->get();

        if (count($carga)>0) {
            return ['estado'=>'success', 'mensaje'=>'si hay datos','body'=>$carga];
        }
        return ['estado'=>'failed','body'=>'','mensaje'=>'No hay datos en la tabla'];
    }
    // public function actualizar_datos_carga(Request $r)
    // {
    //     $carga = SocioCarga::where([
    //                     'activo' => 'S',
    //                     'socio_id' => $r->id,
    //             ])->first();

    //     switch ($r->campo) {
    //         case 'tipo_carga_id':
    //             # code...
    //         break;
    //         case 'rut':
    //             # code...
    //         break;
    //         case 'fecha_nacimiento':
    //             # code...
    //         break;
    //         case 'nombres':
    //             # code...
    //         break;
    //         case 'apellido_paterno':
    //             # code...
    //         break;
    //         case 'apellido_materno':
    //             # code...
    //         break;
    //         case 'direccion':
    //             # code...
    //         break;
    //         case 'celular':
    //             # code...
    //         break;
    //         case 'establecimiento':
    //             # code...
    //         break;
            
    //         default:
    //             # code...
    //             break;
    //     }
    // }

// -------------------------------------------------------------------------------

// -- INICIO DATOS  PADRES Y SUEGROS

    public function guardar_datos_padres_suegros(Request $r)
    {
        $rut_limpio = $this->limpiar($r->rut);

        $verify_i = SocioPadresSuegros::where(['rut'=> $rut_limpio ,'activo'=>'S','socio_id'=>$r->socio_id])->first();

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
    }
    public function traer_datos_padres_suegros($socio_id)
    {
        $listar = SocioPadresSuegros::where(['activo'=>'S','socio_id'=>$socio_id])->get();
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
                'mensaje' => 'failed',
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
            'rut'=> $rut_limpio ,
            'activo'=>'S',
            'socio_id'=>$r->socio_id,
            'id' => $r->socio_padre_suegro_id
        ])->first();
    }


// -- FIN DATOS PADRES Y SUEGROS    
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
                //'archivo' => 'required|mimes:doc,pdf,docx',
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
                //'archivo' => 'required|mimes:doc,pdf,docx',
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
            ]);

 
            if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
            return ['estado' => 'success', 'mensaje' => 'success'];
    }


}
