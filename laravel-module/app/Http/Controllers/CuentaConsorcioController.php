<?php

namespace App\Http\Controllers;

use App\CuentaConsorcio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
      ///VALIDAR SI ESTA DESVINCULADO EL SOCIO/**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
      /**/  $desv = DB::select("SELECT                                                                /**/
      /**/                          concat(nombres,' ',a_paterno,' ',a_materno) nombre                /**/
      /**/                      FROM socios where id = $r->socio_id and fecha_egreso is not null");   /**/
      /**/  if (count($desv)>0) {                                                                     /**/
      /**/      $nombre = $desv[0]->nombre;                                                           /**/
      /**/      return ['estado' => 'failed', 'mensaje'=> $nombre.' se ha desvinculado'];             /**/
      /**/  }                                                                                         /**/
      /**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/

      // si no pues sigue su camino ->

        switch ($r->tipo_consorcio) {
            case '1': // DIA DE SUELDO
                $ds = CuentaConsorcio::insertar_ds($r);
                return $ds;
            break;

            case '2': // DESCUENTOS

                $ds = CuentaConsorcio::insertar_cex($r);
                return $ds;
            break;
            
            default:
                # code...
                break;
        }



    }


    public function insertar_cex_desde_tabla(Request $r)
    {
        $cc_id ='';
        $mes ='';
        $tipo_pago = '2';
        $tipo_retiro ='';
        $porcentaje='';
        $monto='';

        $ds = CuentaConsorcio::insertar_cex_desde_table($r);
        return $ds;
    
        
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

    public function calcular_dia_sueldo($socio_id, $porc, $desc, $mes)
    {
        $dia_sueldo = CuentaConsorcio::calcular_dia_sueldo($socio_id);

        $ds = (int)$dia_sueldo->dia_sueldo;
        $mult = $ds * $porc;
        $result = $ds - $mult;
        echo $result; 
         return ['ds' => $ds,'porc ' => $result];

        // content
    }
    public function calcular_dia_sueldo_manual($dia_sueldo, $porc, $desc, $mes)
    {

        $ds = (int)$dia_sueldo;
        $mult = $ds * $porc;
        $result = $ds - $mult;
        echo $result; 
         return ['ds' => $ds,'porc ' => $result];

        // content
    }
}
