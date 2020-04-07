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

    public function TraerTemas($anio, $mes, $tipo)
    {
        return SecTemas::traerTemas($anio, $mes, $tipo);
    }
}
