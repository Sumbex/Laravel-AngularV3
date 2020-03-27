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

    public function ActualizarNota(Request $request)
    {
        return NotasCuentas::actualizarNota($request);
    }

    public function TraerNotas($anio, $mes)
    {
        return NotasCuentas::traerNotas($anio, $mes);
    }

    public function TraerCuentas()
    {
        return NotasCuentas::traerCuentas();
    }

    public function DatosBasicos()
    {
        return NotasCuentas::datosBasicos();
    }
}
