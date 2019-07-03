<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CajaChica;


class CajaChicaController extends Controller
{
    public function IngresarCajaChica(Request $request){
        return CajaChica::ingresarCajaChica($request);
    }

    public function ExisteCajaChica($anio, $mes){
        return CajaChica::existeCajaChica($anio, $mes);
    }

    public function TraerCajaChica($anio, $mes){
        return CajaChica::traerCajaChica($anio, $mes);
    }
}
