<?php

namespace App\Http\Controllers;

use App\ArchivadorBienestar;
use Illuminate\Http\Request;

class ArchivadorBienestarController extends Controller
{
    public function IngresarArchivo(Request $request)
    {
        return ArchivadorBienestar::ingresarArchivo($request);
    }

    public function ActualizarArchivo(Request $request)
    {
        return ArchivadorBienestar::actualizarArchivo($request);
    }

    public function TraerArchivos($anio, $mes)
    {
        return ArchivadorBienestar::traerArchivos($anio, $mes);
    }

    public function TraerTipos()
    {
        return ArchivadorBienestar::traerTipos();
    }
}
