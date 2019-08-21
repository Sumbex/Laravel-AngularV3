<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PortalSocio;
use App\PortalSocioCuentaSindical;

class PortalSocioController extends Controller
{
    //------------------------------------------>rutas portal socio
    public function LoginSocios(Request $request)
    {
        return PortalSocio::loginSocios($request);
    }

    public function SocioLogeado()
    {
        return PortalSocio::socioLogeado();
    }

    public function CambiarContrasena(Request $request)
    {
        return PortalSocio::cambiarContrasena($request);
    }

    public function ModificarDatosSocios(Request $request)
    {
        return PortalSocio::modificarDatosSocios($request);
    }

    public function IngresarDatosBasicosSocio(Request $request)
    {
        return PortalSocio::ingresarDatosBasicosSocio($request);
    }

    public function ModificarDatosBasicosSocio(Request $request)
    {
        return PortalSocio::modificarDatosBasicosSocio($request);
    }

    public function TraerDatosBasicosSocios()
    {
        return PortalSocio::traerDatosBasicosSocios();
    }

    public function TraerArchivoResumen()
    {
        return PortalSocio::traerArchivoResumen();
    }

    public function IngresarConyugeSocio(Request $request)
    {
        return PortalSocio::ingresarConyugeSocio($request);
    }

    public function TraerDatosConyuge()
    {
        return PortalSocio::traerDatosConyuge();
    }

    public function IngresarBeneficiariosSocio(Request $request)
    {
        return PortalSocio::ingresarBeneficiariosSocio($request);
    }

    public function TraerDatosBeneficiariosSocios()
    {
        return PortalSocio::traerDatosBeneficiariosSocios();
    }

    public function IngresarDatosPadresSuegrosSocio(Request $request)
    {
        return PortalSocio::ingresarDatosPadresSuegrosSocio($request);
    }

    public function TraerDatosPadresSuegrosSocio()
    {
        return PortalSocio::traerDatosPadresSuegrosSocio();
    }

    public function IngresarDatosCargasSocio(Request $request)
    {
        return PortalSocio::ingresarDatosCargasSocio($request);
    }

    public function TraerDatosCargasSocio()
    {
        return PortalSocio::traerDatosCargasSocio();
    }
    //------------------------------------------>rutas portal socio

    //------------------------------------------>rutas datos basicos
    public function TraerAnios()
    {
        return PortalSocio::traerAnios();
    }

    public function AnioActual()
    {
        return PortalSocio::anioActual();
    }

    public function TraerMeses()
    {
        return PortalSocio::traerMeses();
    }

    public function MesActual()
    {
        return PortalSocio::mesActual();
    }
    //------------------------------------------>rutas datos basicos

    //------------------------------------------>rutas portal socio cs
    public function TraerCuentaSindical($anio, $mes)
    {
        return PortalSocioCuentaSindical::traerCuentaSindicalTotal($anio, $mes);
    }
    //------------------------------------------>rutas portal socio cs

    //--------------------------------------------------------------------------------
    public function CrearUsuarioSocio(Request $request)
    {
        return PortalSocio::crearUsuarioSocio($request);
    }

    public function BorrarUsuarioSocio(Request $request)
    {
        return PortalSocio::borrarUsuarioSocio($request);
    }
    //--------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------
    public function CrearUsuariosAdmin(Request $request)
    {
        return PortalSocio::crearUsuariosAdmin($request);
    }

    public function BorrarUsuariosAdmin(Request $request)
    {
        return PortalSocio::borrarUsuariosAdmin($request);
    }
    //--------------------------------------------------------------------------------

    //------------------------------------------------FUNCION TEST------------------------------------------------//
    public function FuncionTest($anio, $mes)
    {
        return PortalSocioCuentaSindical::totalesCuentaSindical($anio, $mes);
    }
    //------------------------------------------------FUNCION TEST------------------------------------------------//
}
