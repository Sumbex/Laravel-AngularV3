<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchivosSocioController extends Controller
{
    public function MostrarDocumentoGeneral($idSocio)
    {
        $traer = DB::Select ("SELECT archivo,socio_id from socios_datos_basicos
        where socio_id = $idSocio and activo = 'S' and archivo is not null");
        if(!empty($traer)){
            return["estado" => "success" , "body" => $traer];      
        }
            return["estado" => "failed" , "mensaje" => "No se a econtrado el documento, compruebe que el documento este subido."];
    }

    public function MostrarRegistroCivil($idSocio)
    {
        $traer = DB::Select("SELECT socio_id,archivo from socio_conyuge 
        where socio_id = $idSocio and activo = 'S' and archivo is not null");
        if(!empty($traer)) {
            return["estado" => "success" , "body" => $traer];
        }
            return["estado" => "failed" , "mensaje" => "No se a encontrado el documento, compruebe que el documento este subido."];
    }

    public function MostrarCertificadoNacimiento($idCarga)
    {
        $traer  = DB::select("SELECT id,socio_id,archivo from cargas_legales_socio
        where id = $idCarga and activo = 'S' and archivo is not null");
         if (!empty($traer)) {
            return["estado" => "success" , "body" => $traer];
        }
            return["estado" => "failed" , "mensaje" => "No se a encontrado el documento, compruebe que el documento este subido."];
    }

}
