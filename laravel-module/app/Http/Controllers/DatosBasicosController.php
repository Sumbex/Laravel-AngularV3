<?php

namespace App\Http\Controllers;

use App\TipoCUentaSindicato;
use App\User;
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
    	return DB::table('definicion')->get();
    }
    public function listar_tipo_cuenta_sindicato()
    {
    	return DB::table('tipo_cuenta_sindicato')->get();
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
        $user = User::where('email', $r->rut)->orWhere('rut', $r->rut)->first();

        if (Hash::check($r->password, $user->password)) {
            return $user->id;
        }
        return 0;
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
                'password' => 'required|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required',
                'new_password' => 'required'
               
            ],
            [
                'password.required' => 'La contraseña es necesaria',
                'password.same' => 'La contraseña actual con la  confirmacion no son iguales',
                'confirm_password.required' => 'Confirme su contraseña',
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
}
