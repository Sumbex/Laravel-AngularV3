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

    public function filtrar($search='')
    {
        if ($search == '') {
            $this->listar();
        }
        $return = CuentaConsorcio::filtrar($search);
        return Response()->json($return);
    }

    public function insertar(Request $r)
    {

        switch ($r->tipo_consorcio) {
            case '1': // DIA DE SUELDO
                $ds = CuentaConsorcio::insertar_ds($r);
                return $ds;
            break;

            case '2': // CUENTA EXTRAORDINARIA

                $ds = CuentaConsorcio::insertar_cex($r);
                return $ds;
            break;
            
            default:
                # code...
                break;
        }



    }

    public function cuenta_consorcio($anio_id)
    {
        return CuentaConsorcio::tabla_consorcio($anio_id);
        
    }
    public function totales_cuenta_consorcio($anio_id)
    {

        return CuentaConsorcio::totales_mensuales_anual($anio_id);
    }

    public function total_anual_socio($anio_id)
    {
        return CuentaConsorcio::total_anual_socio($anio_id);
    }

    public function filtrar_tabla_consorcio($anio_id, $search='')
    {
        return CuentaConsorcio::filtrar_tabla_consorcio($anio_id, $search);
    }
    // TABLA DESVINCULADOS

    public function tabla_desvinculados($anio_id)
    {
         return CuentaConsorcio::tabla_desvinculados($anio_id);
    }
}
