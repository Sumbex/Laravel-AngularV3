<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PortalSocio;

class PortalSocioController extends Controller
{
    public function LoginSocios(Request $request)
    {
        return PortalSocio::loginSocios($request);
    }

    public function ActualizarDatosSocios(Request $request)
    {
        return PortalSocio::actualizarDatosSocios($request);
    }

    public function SocioLogeado()
    {
        return PortalSocio::socioLogeado();
    }
}
