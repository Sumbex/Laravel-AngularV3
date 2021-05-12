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


    public function lista_cumpleanios(){
        $socio =  Socios::all();
        $arreglo = [];
        $sum =0;
        foreach ($socio as $key) {
            $nacimiento = Carbon::parse($key->fecha_nacimiento)->format('d-m');
            $hoy = Carbon::now()->format('d-m');

            if($nacimiento === $hoy){
                $arreglo[$sum]= $key;
                $arreglo[$sum]['edad'] = $this->calculaedad($key->fecha_nacimiento);
                $sum++;
            }


        }

        return $arreglo;
    }

    function calculaedad($fechanacimiento){
        list($ano,$mes,$dia) = explode("-",$fechanacimiento);
        $ano_diferencia  = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia   = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
          $ano_diferencia--;
        return $ano_diferencia;
      }
}