<?php

namespace App\Http\Controllers;

use App\SecReuniones;
use Illuminate\Http\Request;

class SecReunionesController extends Controller
{
    public function TraerTipoReunion()
    {
        return SecReuniones::traerTipoReunion();
    }

    public function CrearReunion(Request $request)
    {
        return SecReuniones::crearReunion($request);
    }

    public function TraerReunionActiva()
    {
        return SecReuniones::traerReunionActiva();
    }

    public function ModificarReunionActiva(Request $request)
    {
        return SecReuniones::modificarReunionActiva($request);
    }
}
