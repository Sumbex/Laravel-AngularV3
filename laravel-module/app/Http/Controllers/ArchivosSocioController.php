<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchivosSocioController extends Controller
{
    public function MostrarDocumentoGeneral($idSocio)
    {
        $traer = DB::Select ("SELECT archivo,socio_id from socios_datos_basicos
        where socio_id = $idSocio and activo = 'S'");
        return $traer;

    }
}
