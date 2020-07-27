<?php

namespace App\Http\Controllers;

use App\ActividadesDirectorio;
use Illuminate\Http\Request;

class ActividadesDirectorioController extends Controller
{
    public function IngresarActividad(Request $request)
    {
        return ActividadesDirectorio::ingresarActividad($request);
    }

    public function CambiarEstadoActividad(Request $request)
    {
        return ActividadesDirectorio::cambiarEstadoActividad($request);
    }

    public function TraerActividades($anio, $mes)
    {
        return ActividadesDirectorio::traerActividades($anio, $mes);
    }

    public function TraerTiposActividades()
    {
        return ActividadesDirectorio::traerTiposActividades();
    }
}
