<?php

namespace App\Http\Controllers;

use App\TipoCUentaSindicato;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DatosBasicosController extends Controller
{
    public function listar_anios()
    {
    	return DB::table('anio')->orderBy('descripcion','desc')->get();
    }
    public function anio_actual()	
    {
    	$anio = DB::select("select date_part('year',now()) as anio");
        $anio_db = DB::table('anio')->select(['id','descripcion'])
        ->where(['activo'=>'S', 'descripcion'=>$anio[0]->anio])->first();
    	return response()->json($anio_db);
    }

    public function listar_meses()
    {
    	return DB::table('mes')->orderBy('id','asc')->get();
    }
    public function mes_actual()
    {
    	$mes_actual = DB::select("select date_part('month',now()) as mes");
    	$id = $mes_actual[0]->mes;
    	return $this->get_mes($id);
    }
    public function listar_definicion()
    {
    	return DB::table('definicion')->where('activo','S')->get();
    }
    public function listar_tipo_cuenta_sindicato()
    {
    	return DB::table('tipo_cuenta_sindicato')->where('activo','S')->get();
    }


    public function get_mes($id)
    {
    	 switch ($id) {
    		case '1': $mes=['id'=>$id, 'descripcion'=>'Enero' ]; break;
    		case '2': $mes=['id'=>$id, 'descripcion'=> 'Febrero']; break;
    		case '3': $mes=['id'=>$id, 'descripcion'=> 'Marzo']; break;
    		case '4': $mes=['id'=>$id, 'descripcion'=> 'Abril']; break;
    		case '5': $mes=['id'=>$id, 'descripcion'=> 'Mayo']; break;
    		case '6': $mes=['id'=>$id, 'descripcion'=> 'Junio']; break;
    		case '7': $mes=['id'=>$id, 'descripcion'=> 'Julio']; break;
    		case '8': $mes=['id'=>$id, 'descripcion'=> 'Agosto']; break;
    		case '9': $mes =['id'=>$id, 'descripcion'=> 'Septiembre']; break;
    		case '10': $mes =['id'=>$id, 'descripcion'=> 'Octubre'];  break;
    		case '11': $mes =['id'=>$id, 'descripcion'=> 'Noviembre']; break;
    		case '12': $mes =['id'=>$id, 'descripcion'=> 'Diciembre'];  break;
    		
    		
    	}

    	return $mes;
    }

    public function confirmar_usuario(Request $r)
    {

        $permiso = $this->usuarios_con_permisos($r->rut,$r->estado);
        
        try{

           

            if ($permiso == true) {
                $user = User::where('email', $r->rut)->orWhere('rut', $r->rut)->first();

                if (Hash::check($r->password, $user->password)) {
                    return $user->id;
                }
                return 0;
            }else{

                return 0;
            }
        
        }catch(QueryException $e){
            return 0;
        }
        catch(\Exception $e){
            return 0;
        }
    }

    public function cambiar_password(Request $r)
    {
        $validar = $this->validar_password($r);

        if ($validar['estado'] == "true") {

                $user = User::find(Auth::user()->id);

                if (Hash::check($r->password, $user->password)) {
                    $user->password = bcrypt($r->new_password);
                    if ($user->save()) {
                        return ['estado'=>'success'];
                    }else{
                        return ['estado'=>'filed'];
                    }
                }else{
                    return ['estado' => "false", 'mensaje' =>'Contraseña actual no valida'];
                }
        }else{
            return $this->validar_password($r);
        }
       

    }

    public function validar_password($request)
    {

         $validator = Validator::make($request->all(), 
            [
                'password' => 'required',
                'new_password' => 'required|required_with:conf_new_password|same:conf_new_password',
                'conf_new_password' => 'required'
               
            ],
            [
                'password.required' => 'La contraseña actual es necesaria',
                'new_password.same' => 'La nueva contraseña con la confirmacion no son iguales',
                'conf_new_password.required' => 'Confirme su nueva contraseña',
                'new_password.required' => 'La nueva contraseña es necesaria',
               
            ]);

 
            if ($validator->fails()){ return ['estado' => "false", 'mensaje' => $validator->errors()];}
            return ['estado' => "true", 'mensaje' => 'success'];
    }
    public function usuario_logeado()
    {
        $user = User::find(Auth::user()->id);
        return $user;
    }

    public function usuarios_con_permisos($rut, $estado)
    {
        $usuarios = DB::table('permisos')->get();

      
        //este objeto tiene los permisos en todos los modulos administrativos (S/N)
        // $usuarios = [
        //     [ //alejandro
        //         'rut' => '188056520',
        //         'ingresar_cierre_inicio_mes' => 'S', // el inicio y cierre del mes
        //         'ingresar_cs' => 'S', // ingre4sar item en cuenta sindical
        //         'modificar_cs' => 'S', // actualizar en cuenta sindical
        //         'ingresar_total_camping' =>'S', //modal detalle camping input total camping
        //         'modificar_detalle_camping' => 'S', // detalles de la tabla camping
        //         'ingresar_socio' => 'S',
        //         'modificar_socio' => 'S',
        //         'ingresar_socio_beneficios' =>'S', // socio beneficios
        //         'modificar_socio_beneficios' => 'S', //socio beneficios
        //         'ingresar_cch' => 'S',
        //         'modificar_cch' => 'S',
        //         'ingresar_cb' => 'S',
        //         'modificar_cb' => 'S',
        //         'calcular_descuento_cc' => 'S',
        //         'aplicar_descuento_cc' => 'S'
        //     ], 
        //     [ // david pass 1028
        //         'rut' => '190523047',
        //         'ingresar_cierre_inicio_mes' => 'S',
        //         'ingresar_cs' => 'S',
        //         'modificar_cs' => 'S',
        //         'ingresar_total_camping' =>'S',
        //         'modificar_detalle_camping' => 'S',
        //         'ingresar_socio' => 'S',
        //         'modificar_socio' => 'S',
        //         'ingresar_socio_beneficios' =>'S', 
        //         'modificar_socio_beneficios' => 'S',
        //         'ingresar_cch' => 'S',
        //         'modificar_cch' => 'S',
        //         'ingresar_cb' => 'S',
        //         'modificar_cb' => 'S',
        //         'calcular_descuento_cc' => 'S',
        //         'aplicar_descuento_cc' => 'S'
        //     ], 
        //     [ //bryan vidal
        //         'rut' => '185227839',
        //         'ingresar_cierre_inicio_mes' => 'S', // el inicio y cierre del mes
        //         'ingresar_cs' => 'S', // ingre4sar item en cuenta sindical
        //         'modificar_cs' => 'S', // actualizar en cuenta sindical
        //         'ingresar_total_camping' =>'S', //modal detalle camping input total camping
        //         'modificar_detalle_camping' => 'S', // detalles de la tabla camping
        //         'ingresar_socio' => 'S',
        //         'modificar_socio' => 'S',
        //         'ingresar_socio_beneficios' =>'S', // socio beneficios
        //         'modificar_socio_beneficios' => 'S', //socio beneficios
        //         'ingresar_cch' => 'S',
        //         'modificar_cch' => 'S',
        //         'ingresar_cb' => 'S',
        //         'modificar_cb' => 'S',
        //         'calcular_descuento_cc' => 'S',
        //         'aplicar_descuento_cc' => 'S'
        //     ],
        //     [ //bryan montecino
        //         'rut' => '191107861',
        //         'ingresar_cierre_inicio_mes' => 'S', // el inicio y cierre del mes
        //         'ingresar_cs' => 'S', // ingre4sar item en cuenta sindical
        //         'modificar_cs' => 'S', // actualizar en cuenta sindical
        //         'ingresar_total_camping' =>'S', //modal detalle camping input total camping
        //         'modificar_detalle_camping' => 'S', // detalles de la tabla camping
        //         'ingresar_socio' => 'S',
        //         'modificar_socio' => 'S',
        //         'ingresar_socio_beneficios' =>'S', // socio beneficios
        //         'modificar_socio_beneficios' => 'S', //socio beneficios
        //         'ingresar_cch' => 'S',
        //         'modificar_cch' => 'S',
        //         'ingresar_cb' => 'S',
        //         'modificar_cb' => 'S',
        //         'calcular_descuento_cc' => 'S',
        //         'aplicar_descuento_cc' => 'S'
        //     ], 
        //     [ // don emilio 
        //         'rut' => '9508866k',
        //         'ingresar_cierre_inicio_mes' => 'S',
        //         'ingresar_cs' => 'S',
        //         'modificar_cs' => 'S',
        //         'ingresar_total_camping' =>'S',
        //         'modificar_detalle_camping' => 'S',
        //         'ingresar_socio' => 'S',
        //         'modificar_socio' => 'S',
        //         'ingresar_socio_beneficios' =>'S', 
        //         'modificar_socio_beneficios' => 'S',
        //         'ingresar_cch' => 'S',
        //         'modificar_cch' => 'S',
        //         'ingresar_cb' => 'S',
        //         'modificar_cb' => 'S',
        //         'calcular_descuento_cc' => 'S',
        //         'aplicar_descuento_cc' => 'S'
        //     ], 
        //     [ // dña Oriana
        //         'rut' => '81951845',
        //         'ingresar_cierre_inicio_mes' => 'N',
        //         'ingresar_cs' => 'S',
        //         'modificar_cs' => 'N',
        //         'ingresar_total_camping' =>'S',
        //         'modificar_detalle_camping' => 'N',
        //         'ingresar_socio' => 'S',
        //         'modificar_socio' => 'S',
        //         'ingresar_socio_beneficios' =>'S', 
        //         'modificar_socio_beneficios' => 'S',
        //         'ingresar_cch' => 'S',
        //         'modificar_cch' => 'N',
        //         'ingresar_cb' => 'S',
        //         'modificar_cb' => 'N',
        //         'calcular_descuento_cc' => 'N',
        //         'aplicar_descuento_cc' => 'N'
        //     ]
             
        // ];



        foreach ($usuarios as $u) {
            
            if ($u->rut == $rut) {

                switch ($estado) {
                    case 'ingresar_cs':
                        return ($u->ingresar_cs =='S')? true:false;
                    break;
                    case 'modificar_cs':
                        return ($u->modificar_cs =='S')? true:false;
                    break;
                    case 'ingresar_cierre_inicio_mes':
                    
                        return ($u->ingresar_cierre_inicio_mes =='S')? true:false;
                    break;
                    case 'ingresar_total_camping':
                    
                        return ($u->ingresar_total_camping =='S')? true:false;
                    break;
                    case 'modificar_detalle_camping':
                    
                        return ($u->modificar_detalle_camping =='S')? true:false;
                    break;
                    case 'ingresar_socio':
                    
                        return ($u->ingresar_socio =='S')? true:false;
                    break;
                    case 'modificar_socio':
                    
                        return ($u->modificar_socio =='S')? true:false;
                    break;
                    case 'ingresar_socio_beneficios':
                    
                        return ($u->ingresar_socio_beneficios =='S')? true:false;
                    break;
                    case 'modificar_socio_beneficios':
                    
                        return ($u->modificar_socio_beneficios =='S')? true:false;
                    break;
                    case 'ingresar_cch':
                    
                        return ($u->ingresar_cch =='S')? true:false;
                    break;
                    case 'modificar_cch':
                    
                        return ($u->modificar_cch =='S')? true:false;
                    break;
                    case 'ingresar_cb':
                    
                        return ($u->ingresar_cb =='S')? true:false;
                    break;
                    case 'modificar_cb':
                    
                        return ($u->modificar_cb =='S')? true:false;
                    break;

                    case 'calcular_descuento_cc':
                    
                        return ($u->calcular_descuento_cc =='S')? true:false;
                    break;
                    case 'aplicar_descuento_cc':
                    
                        return ($u->aplicar_descuento_cc =='S')? true:false;
                    break;
                    case 'ingresar_go'://gastos operacionales
                    
                        return ($u->ingresar_go =='S')? true:false;
                    break;
                    case 'modificar_go'://gastos operacionales
                    
                        return ($u->modificar_go =='S')? true:false;
                    break;
                    
                    default:
                        return true; // dejar hacer accion en caso que el usuario no este asociado a un permiso en especial
                    break;
                }
                
            }
            
            
        }
        return false;
    }
}
