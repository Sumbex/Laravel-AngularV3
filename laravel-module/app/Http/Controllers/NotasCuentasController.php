<?php

namespace App\Http\Controllers;

use App\NotasCuentas;
use Illuminate\Http\Request;

class NotasCuentasController extends Controller
{
    public function IngresarNota(Request $request)
    {
        return NotasCuentas::ingresarNota($request);
    }

    public function TraerNotas($anio, $mes, $cuenta)
    {
        return NotasCuentas::traerNotas($anio, $mes, $cuenta);
    }

    public function TraerCuentas()
    {
        return NotasCuentas::traerCuentas();
    }
}
