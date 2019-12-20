<?php

namespace App\Http\Controllers;

use App\SecDirectorio;
use Illuminate\Http\Request;

class SecDirectorioController extends Controller
{
    public function registroDirectorio(Request $datos){
        return SecDirectorio::registro_directorio($datos);
     }
    public function visualizarDirectorio(){
        return SecDirectorio::visualizar_directorio();
     }
    public function visualizarDirectorioDetalle($id){
        return SecDirectorio::visualizar_directorio_detalle($id);
     }
}
