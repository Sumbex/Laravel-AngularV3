<?php

namespace App\Http\Controllers;

use App\SecTemas;
use Illuminate\Http\Request;

class SecTemasController extends Controller
{
    public function IngresarTema(Request $request)
    {
        return SecTemas::ingresarTema($request);
    }

    public function ActualizaTema(Request $request)
    {
        return SecTemas::actualizaTema($request);
    }

    public function CerrarTema(Request $request)
    {
        return SecTemas::cerrarTema($request);
    }

    public function CancelarTema(Request $request)
    {
        return SecTemas::cancelarTema($request);
    }

    public function TraerTemas($anio, $mes, $tipo)
    {
        return SecTemas::traerTemas($anio, $mes, $tipo);
    }

    public function TraerTemasActivos()
    {
        return SecTemas::traerTemasActivos();
    }

    public function TraerTiposTemas()
    {
        return SecTemas::traerTiposTemas();
    }
}
