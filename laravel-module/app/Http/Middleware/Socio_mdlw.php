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
        $rol = Auth::guard('socio_api')->user()->rol;

        if ($rol == '10') {
            return $next($request);
        }
            
    }
}
