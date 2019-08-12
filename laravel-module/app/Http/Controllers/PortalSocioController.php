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

    public function ModificarDatosSocios(Request $request)
    {
        return PortalSocio::modificarDatosSocios($request);
    }

    public function SocioLogeado()
    {
        return PortalSocio::socioLogeado();
    }

    public function TraerDatosBasicosSocios()
    {
        return PortalSocio::traerDatosBasicosSocios();
    }

    public function IngresarDatosBasicosSocio(Request $request)
    {
        return PortalSocio::ingresarDatosBasicosSocio($request);
    }
}
