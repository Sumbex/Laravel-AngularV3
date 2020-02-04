<?php

namespace App\Http\Controllers;

use App\Acta;
use Illuminate\Http\Request;

class ActasController extends Controller
{
    public function crear(Request $r)
    {
        return Acta::insertar($r);
    }

    public function listar()
    {
        $var = Acta::all();
        if ($var) {
            return [
                'estado'=>'success',
                'data' =>Acta::all()
            ];
        }
    }
}


