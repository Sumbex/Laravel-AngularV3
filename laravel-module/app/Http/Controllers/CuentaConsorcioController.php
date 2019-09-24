<?php

namespace App\Http\Controllers;

use App\CuentaConsorcio;
use Illuminate\Http\Request;

class CuentaConsorcioController extends Controller
{
    public function traer_socios_consorcios()
    {
        return CuentaConsorcio::listar_formulario();
    }
}
