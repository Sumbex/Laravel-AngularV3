<?php

namespace App\Http\Controllers;

use App\SecLeyes;
use Illuminate\Http\Request;

class SecLeyesController extends Controller
{
    public function IngresarNoticia(Request $request)
    {
        return SecLeyes::ingresarNoticia($request);
    }

    public function TraerNoticias(){
        return SecLeyes::traerNoticias();
    }
    
}
