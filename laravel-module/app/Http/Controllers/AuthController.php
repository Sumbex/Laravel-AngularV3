<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

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
		
	    $user = User::where('email', $request->email)
		            ->orWhere('rut', $request->email)->first();

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
		            'token' => $token
		        ])
		        ->header('Authorization', $token);
		}
		return response(['status'=>'failed']);
	}



	public function logout()
	{
	    JWTAuth::invalidate();
	    return response([
	            'status' => 'success',
	            'msg' => 'Logged out Successfully.'
	        ], 200);
	}
}
