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

    public function TraerActividadesActivas()
    {
        return ActividadesDirectorio::traerActividadesActivas();
    }
}
