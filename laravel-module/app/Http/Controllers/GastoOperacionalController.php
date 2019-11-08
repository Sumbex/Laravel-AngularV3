<?php

namespace App\Http\Controllers;

use App\Cs_gastos_operacionales;
use Illuminate\Http\Request;

class GastoOperacionalController extends Controller
{
    public function guardar(Request $value){
        $go = Cs_gastos_operacionales::guardar($value);
        return $go;
    }

    public function listar($anio, $mes){
        return Cs_gastos_operacionales::listar($anio, $mes);
    }

}
