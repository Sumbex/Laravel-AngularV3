<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchivosSocioController extends Controller
{
    public function MostrarDocumentoGeneral($idSocio)
    {
        $traer = DB::Select ("SELECT archivo,socio_id from socios_datos_basicos
        where socio_id = $idSocio and activo = 'S' and archivo != null");
           if(!empty($traer)){
            return[
                "estado" => "success" , "body" => $traer
            ];
            
        }
        return[
            "estado" => "failed" , "mensaje" => "No se a econtrado el documento"
        ];

    }
}
