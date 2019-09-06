<?php

namespace App\Http\Controllers;

use App\Cuentabienestar;
use Illuminate\Http\Request;

class CuentaBienestarController extends Controller
{
    public function insertar(Request $r)
    {
        $tipo_cuenta = $r->tipo_cuenta_bienestar_id;

        switch ((string)$tipo_cuenta) {
            case '1': // cuenta del gas
                $cuenta_gas = Cuentabienestar::insertar_cuenta_gas($r);
                return $cuenta_gas;
            break;
            
            default:
                # code...
                break;
        }

    }
}
