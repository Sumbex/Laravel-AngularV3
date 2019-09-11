<?php

namespace App\Http\Controllers;

use App\CajaChicaBienestar;
use Illuminate\Http\Request;

class CajaChicaBienestarController extends Controller
{
    public function IngresarCajaChica(Request $request)
    {
        return CajaChicaBienestar::ingresarCajaChica($request);
    }

    public function TraerCajaChicaTotal($anio, $mes)
    {
        return CajaChicaBienestar::traerCajaChicaTotal($anio, $mes);
    }

    public function ModificarCajaChica(Request $request)
    {
        return CajaChicaBienestar::modificarCajaChica($request);
    }
}
