<?php

namespace App\Http\Controllers;

use App\NotasBienestar;
use Illuminate\Http\Request;

class NotasBienestarController extends Controller
{
    public function IngresarNota(Request $request)
    {
        return NotasBienestar::ingresarNota($request);
    }

    public function ActualizarNota(Request $request)
    {
        return NotasBienestar::actualizarNota($request);
    }

    public function TraerNotas($anio, $mes)
    {
        return NotasBienestar::traerNotas($anio, $mes);
    }

    public function TraerCuentas()
    {
        return NotasBienestar::traerCuentas();
    }

    public function DatosBasicos()
    {
        return NotasBienestar::datosBasicos();
    }
}
