<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ActividadesDirectorio;

class PortalSocioSecActividadesController extends Controller
{
    public function TraerActividades($anio, $mes)
    {
        return ActividadesDirectorio::traerActividades($anio, $mes);
    }

    public function TraerTiposActividades()
    {
        return ActividadesDirectorio::traerTiposActividades();
    }
}
