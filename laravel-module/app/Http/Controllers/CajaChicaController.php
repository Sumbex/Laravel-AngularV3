<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CajaChica;


class CajaChicaController extends Controller
{
    //public  $saldo = 100000;

    public function IngresarCajaChica(Request $request)
    {
        return CajaChica::ingresarCajaChica($request);
    }

    public function ExisteCajaChica($anio, $mes)
    {
        return CajaChica::existeCajaChica($anio, $mes);
    }

    public function TraerCajaChica($anio, $mes)
    {
        $existe = $this->ExisteCajaChica($anio, $mes);

        if ($existe['estado'] == 'success') {
            $caja = CajaChica::saldoActualCaja($anio, $mes);
            return $caja;
        } else {
            return $existe;
        }
    }

    public function TotalIngEgre($anio, $mes)
    {
        return CajaChica::totalIngEgre($anio, $mes);
    }
    // Request $request,
    public function ModificarDatos(Request $request)
    {
        return CajaChica::modificarDatos($request);
    }
}
