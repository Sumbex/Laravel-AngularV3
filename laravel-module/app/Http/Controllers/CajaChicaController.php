<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CajaChica;


class CajaChicaController extends Controller
{
    public  $saldo = 100000;
    
    public function IngresarCajaChica(Request $request){
        return CajaChica::ingresarCajaChica($request);
    }

    public function ExisteCajaChica($anio, $mes){
        return CajaChica::existeCajaChica($anio, $mes);
    }

    public function TraerCajaChica($anio, $mes){
        $caja = CajaChica::traerCajaChica($anio, $mes);
        $tomar = true;

        for ($i=0; $i < count($caja); $i++) { 
            switch ($caja[$i]->definicion) {
                case 1:
                    if($tomar == true){
                        $caja[$i]->saldo_actual = $this->saldo + $caja[$i]->monto_ingreso;
                        $tomar = false;
                    }else{
                        $caja[$i]->saldo_actual = $caja[$i-1]->saldo_actual + $caja[$i]->monto_ingreso;
                    }
                break;
                case 2:
                    if($tomar == true){
                        $caja[$i]->saldo_actual = $this->saldo - $caja[$i]->monto_egreso;
                        $tomar = false;
                    }else{
                        $caja[$i]->saldo_actual = $caja[$i-1]->saldo_actual - $caja[$i]->monto_egreso;
                    }
                break;
            }
        }
        return $caja;
    }
    
    public function TotalIngEgre($anio, $mes){
        //$totales = 0;
        $caja = CajaChica::totalIngEgre($anio, $mes);
        return $caja;

    }


}
