<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Socio_mdlw
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

        // return response()->json($logeado);

        if ((string)$logeado->rol == "10") { //si el rol es de socio       
            return $next($request);
        }
        
        return response(['status'=>'failed', 'mensaje'=>'No tienes acceso a esta url']);
    }
}
