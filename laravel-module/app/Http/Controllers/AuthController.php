<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    

    public function login(Request $request)
	{
	    $credentials = $request->only('email', 'password');
	    if ( ! $token = JWTAuth::attempt($credentials)) {
	            return response([
	                'status' => 'error',
	                'error' => 'invalid.credentials',
	                'msg' => 'Invalid Credentials.'
	            ], 400);
	    }
	    return response([
	            'status' => 'success',
	            'token' => $token
	        ])
	        ->header('Authorization', $token);
	}

	public function login_rut(Request $request)
	{	
		try{
			if(!$this->valida_rut($request->email)){
				return ['status'=>'failed', 'mensaje'=>'El rut no es valido'];
			}
			else{//rut si valido

				// config()->set( 'auth.defaults.guard', 'api' );
                // \Config::set('jwt.user', App\User::class);
                // \Config::set('auth.providers.users.model', \App\User::class);
			
			    $user = User::where('rut', $request->email)->first();

				if (Hash::check($request->password, $user->password)) {
					 //$credentials = $request->only('email', 'password');
				    if ( ! $token = JWTAuth::fromUser($user)) {
				            return response([
				                'status' => 'error',
				                'error' => 'invalid.credentials',
				                'msg' => 'Invalid Credentials.'
				            ], 400);
				    }
				    return response([
				            'status' => 'success',
				            'token' => $token,
				            'rol' => (string)$user->rol,
				        ])
				        ->header('Authorization', $token);
				}
				return response(['status'=>'failed', 'mensaje'=>'Contraseña no valida']);
			}
		}catch(\ErrorException $e){
			return ['status'=>'failed', 'Se ha producido un error, verifique si el rut es correcto o existe en la base de datos'];
		}
	}



	public function logout()
	{
	    JWTAuth::invalidate();
	    return response([
	            'status' => 'success',
	            'msg' => 'Logged out Successfully.'
	        ], 200);
	}

	//validar rut metodo---------------------------

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
}
