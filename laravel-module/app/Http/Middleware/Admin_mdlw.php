<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        $rol = Auth::user()->rol;

        if ($rol == "1") { //si el rol es de administrador       
            return $next($request);
        }
    }
}
