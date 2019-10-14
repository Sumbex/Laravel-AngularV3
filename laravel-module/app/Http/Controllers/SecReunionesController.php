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

    public function CancelarReunionActiva(Request $request)
    {
        return SecReuniones::cancelarReunionActiva($request);
    }

    public function CerrarReunionActiva(Request $request)
    {
        return SecReuniones::cerrarReunionActiva($request);
    }

    public function TraerReunionCerradaFinalizada()
    {
        return SecReuniones::traerReunionCerradaFinalizada();
    }

    public function TraerSocioPorRut($rut, $reunion_id)
    {
        return SecReuniones::traerSocioPorRut($rut, $reunion_id);
    }

    public function MarcarAsitenciaSocioReunion(Request $request)
    {
        return SecReuniones::marcarAsitenciaSocioReunion($request);
    }
}
