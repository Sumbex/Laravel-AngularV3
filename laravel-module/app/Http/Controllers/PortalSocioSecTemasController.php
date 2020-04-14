<?php

namespace App\Http\Controllers;

use App\PortalSocioSecTemas;
use Illuminate\Http\Request;

class PortalSocioSecTemasController extends Controller
{
    public function TemasActivos()
    {
        return PortalSocioSecTemas::temasActivos();
    }

    public function IngresarVoto(Request $request)
    {
        return PortalSocioSecTemas::ingresarVoto($request);
    }

    public function TraerConteoVotos($tema)
    {
        return PortalSocioSecTemas::traerConteoVotos($tema);
    }

    public function TraerTipos()
    {
        return PortalSocioSecTemas::traerTipos();
    }

    public function TraerHistorial($anio, $mes, $tipo)
    {
        return PortalSocioSecTemas::traerHistorial($anio, $mes, $tipo);
    }

    public function TraerTipoVotos()
    {
        return PortalSocioSecTemas::traerTipoVotos();
    }
}
