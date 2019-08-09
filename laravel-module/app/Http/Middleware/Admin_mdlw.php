<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Admin_mdlw
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $socio = Auth::guard('socio_api')->user();
        $admin = Auth::user();

        $logeado = !empty($socio)? $socio : $admin;

         //return response()->json($logeado->rol);

        if ((string)$logeado->rol == "1") { //si el rol es de administrador       
            return $next($request);
        }
        
        return response(['status'=>'failed', 'mensaje'=>'No tienes acceso a esta url']);

    }
}
