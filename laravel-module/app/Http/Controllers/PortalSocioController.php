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

    public function CambiarContrasena(Request $request)
    {
        return PortalSocio::cambiarContrasena($request);
    }

    public function TraerDatosBasicosSocios()
    {
        return PortalSocio::traerDatosBasicosSocios();
    }

    public function IngresarDatosBasicosSocio(Request $request)
    {
        return PortalSocio::ingresarDatosBasicosSocio($request);
    }

    public function ModificarDatosBasicosSocio(Request $request)
    {
        return PortalSocio::modificarDatosBasicosSocio($request);
    }

    public function TraerDatosConyuge()
    {
        return PortalSocio::traerDatosConyuge();
    }

    public function IngresarConyugeSocio(Request $request)
    {
        return PortalSocio::ingresarConyugeSocio($request);
    }

    public function IngresarBeneficiariosSocio(Request $request)
    {
        return PortalSocio::ingresarBeneficiariosSocio($request);
    }

    public function TraerDatosBeneficiariosSocios()
    {
        return PortalSocio::traerDatosBeneficiariosSocios();
    }

    public function CrearUsuarioSocio(Request $request)
    {
        return PortalSocio::crearUsuarioSocio($request);
    }

    public function BorrarUsuarioSocio(Request $request)
    {
        return PortalSocio::borrarUsuarioSocio($request);
    }

    public function CrearUsuariosAdmin(Request $request)
    {
        return PortalSocio::crearUsuariosAdmin($request);
    }

    public function BorrarUsuariosAdmin(Request $request)
    {
        return PortalSocio::borrarUsuariosAdmin($request);
    }
}
