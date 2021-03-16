<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Socios;

class CumpleanioController extends Controller
{
    public function contador_cumpleanieros(){

        $socio =  Socios::all();
        $count = 0;
        foreach ($socio as $key) {
            $nacimiento = Carbon::parse($key->fecha_nacimiento)->format('d-m');
            $hoy = Carbon::now()->format('d-m');

            if($nacimiento === $hoy){
                $count = $count + 1;
            }


        }

        return $count;
    }
}
