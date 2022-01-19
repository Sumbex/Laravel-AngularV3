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

    public function listar($anio, $mes, $directiva){
        return Cs_gastos_operacionales::listar($anio, $mes, $directiva);
    }

    public function actualizar(Request $value){
        $go = Cs_gastos_operacionales::modificarGastoOperacional($value);
        return $go;
    }

    public function actualizarSaldoDisponible(Request $value){
        $go = Cs_gastos_operacionales::actualizarSaldoDisponible($value);
        return $go;
    }

    public function validarMontoInicio($directiva){
        $go = Cs_gastos_operacionales::validar_monto_inicio($directiva);
        return $go;
    }

}
