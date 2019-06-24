<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CajaChica;

class CajaChicaController extends Controller
{
    public function IngresarCajaChica(Request $request){
        
        CajaChica::ingresarCajaChica($request);
    }
}
