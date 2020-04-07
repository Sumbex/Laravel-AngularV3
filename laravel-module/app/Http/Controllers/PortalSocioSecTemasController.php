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
}
