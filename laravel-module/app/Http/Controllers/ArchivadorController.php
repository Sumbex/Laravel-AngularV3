<?php

namespace App\Http\Controllers;

use App\Archivador;
use Illuminate\Http\Request;

class ArchivadorController extends Controller
{
    public function IngresarArchivo(Request $request)
    {
        return Archivador::ingresarArchivo($request);
    }

    public function ActualizarArchivo(Request $request)
    {
        return Archivador::actualizarArchivo($request);
    }

    public function TraerArchivos($anio, $mes)
    {
        return Archivador::traerArchivos($anio, $mes);
    }
}
