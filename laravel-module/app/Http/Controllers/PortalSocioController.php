<?php

namespace App\Http\Controllers;

use App\PortalSocio;
use App\CajaChicaBienestar;
use App\PortalSocioCuentaBienestar;
use App\PortalSocioCuentaConsorcio;
use Illuminate\Http\Request;
use App\PortalSocioMisBeneficios;
use App\PortalSocioCuentaSindical;
use App\PortalSocioJustificar;

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

    public function TraerCajaChica($anio, $mes)
    {
        return PortalSocioCuentaSindical::traerCajaChicaTotal($anio, $mes);
    }

    public function TraerCamping($anio, $mes)
    {
        return PortalSocioCuentaSindical::traerCampingTotal($anio, $mes);
    }
    //------------------------------------------>rutas portal socio cs

    //------------------------------------------>rutas portal socio cb
    public function TraerCuentaBienestar($anio, $mes)
    {
        return PortalSocioCuentaBienestar::traerCuentaBienestarTotal($anio, $mes);
    }
    public function TraerCajaChicaBienestar($anio, $mes)
    {
        return PortalSocioCuentaBienestar::traerCajaChicaBienestar($anio, $mes);
    }
    //------------------------------------------>rutas portal socio cb

    //------------------------------------------>rutas portal socio cc
    public function TraerCuentaConsorcioAnual($anio)
    {
        return PortalSocioCuentaConsorcio::traerCuentaConsorcioAnual($anio);
    }
    //------------------------------------------>rutas portal socio cc

    //------------------------------------------>rutas portal socio mb
    public function TraerPrestamos()
    {
        return PortalSocioMisBeneficios::traerPrestamos();
    }

    public function TraerPagosPrestamos($id, $tipo)
    {
        return PortalSocioMisBeneficios::traerPagosPrestamos($id, $tipo);
    }

    public function TraerPagosAbonos($id, $tipo)
    {
        return PortalSocioMisBeneficios::traerPagosAbonos($id, $tipo);
    }

    public function TraerNacimientos()
    {
        return PortalSocioMisBeneficios::traerNacimientos();
    }

    public function TraerFallecimientos()
    {
        return PortalSocioMisBeneficios::traerFallecimientos();
    }

    public function TraerGastosMedicos()
    {
        return PortalSocioMisBeneficios::traerGastosMedicos();
    }

    public function TraerMisAhorros($anio)
    {
        return PortalSocioMisBeneficios::traerMisAhorros($anio);
    }

    public function SociosDesvinculados($anio)
    {
        return PortalSocioMisBeneficios::sociosDesvinculados($anio);
    }
    //------------------------------------------>rutas portal socio mb

    //------reunion 
    public function JustificarSocio(Request $request)
    {
        return PortalSocioJustificar::justificarSocio($request);
    }
    //------reunion 

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
        return PortalSocioCuentaSindical::traerPrestamos($anio, $mes);
    }

    public function FuncionTest2()
    {
        return PortalSocioMisBeneficios::traerPrestamos();
    }
    //------------------------------------------------FUNCION TEST------------------------------------------------//
}
