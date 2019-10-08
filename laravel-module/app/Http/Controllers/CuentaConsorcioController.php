<?php

namespace App\Http\Controllers;

use App\Socios;
use App\CcPagoBeneficio;
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
        $listar = CuentaConsorcio::tabla_consorcio($anio_id);

        foreach ($listar as $key) {
            if ($key->vinculado == 'N') {
                $ultimo_ds = CuentaConsorcio::calcular_dia_sueldo($key->socio_id);
                $key->total_menos_ds = (int)$key->monto_total_socio - (int)$ultimo_ds['dia_sueldo'];
            }
        }

        return $listar;
        
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

    public function socios_sin_pb()
    {
        $listar = Socios::select([
            'id',
            DB::raw("concat(nombres,' ',a_paterno,' ',a_materno,' - egreso: ',to_char(fecha_egreso, 'dd-mm-yyyy')) as sin_pb")
        ])->where([
            'retiro_pago_beneficio' => 'N'
        ])->get();

        if (count($listar)>0) {
            return $listar;
        }
    }

    public function descontar_dia_sueldo($porc, $mes, $anio)
    {

        $cc = CuentaConsorcio::select([
            'socio_id',
            'monto_mes_ds_'.$mes
        ])
        ->where([
            'anio_id' => $anio
        ])->get();
        
        $sum=0;
        $monto_beneficio =0;

        foreach ($cc as $key) {
          
                if ($key['monto_mes_ds_'.$mes] != 0 || $key['monto_mes_ds_'.$mes] != null ) {
                    
                    $update = CuentaConsorcio::where([
                        'socio_id' => $key->socio_id,
                        'anio_id' => $anio
                    ])->first();


                    $ds = (int) $key['monto_mes_ds_'.$mes];
                    $mult = $ds * $porc;
                    $result = $mult; //$ds - $mult;

                    $update['monto_mes_cex_'.$mes] = round($result);
                    $monto_beneficio = $monto_beneficio + round($result);
                    if ($update->save()) {
                        $sum++;
                    }
                }
            
        }

    
        if ($sum > 0) {
            return [
                'estado'=>'success',
                'mensaje'=>'Se han calculado '.$sum.' registro(s)',
                'monto_beneficio' => $monto_beneficio
            ];
        }
        return ['estado'=>'failed','mensaje'=>'ningun dato calculado'];

    }
    public function aplicar_descuento_dia_sueldo($socio_id, $porc, $desc, $mes, $anio)
    {
        $sum =0;
        $verify = CcPagoBeneficio::where([
                    'anio_id' =>$anio,
                    'mes_id' => $mes,
                    'socio_id' => $socio_id
                ])->first();
        
        if ($verify) {

            if ($verify->descripcion != $desc) {
                return ['estado'=>'failed','mensaje'=>'Ya no puede cambiar el tipo de descuento'];
            }

            $verify->mes_id = $mes;
            $verify->descripcion = $desc;
            $verify->porcentaje = $porc;
            $verify->save();
            $sum++;

        }else{
            $pb = new CcPagoBeneficio;
            $pb->socio_id = $socio_id;
            $pb->anio_id = $anio;
            $pb->mes_id = $mes;
            $pb->descripcion = $desc;
            $pb->porcentaje = $porc;
            $pb->save();
            $sum++;

        }

        $desv = CuentaConsorcio::where(['anio_id'=> $anio, 'socio_id'=>$socio_id])->first();
        $desv->vinculado = 'N';
        $desv->save();
        $sum++;

        if ($sum == 2) {
            return [
                'estado' => 'success',
                'menjsaje' => 'Pago de beneficio y desvinculacion exitosa'
            ];
        }
        return [
                'estado' => 'failed',
                'menjsaje' => 'Pago de beneficio o desvinculacion erronea'
        ];
    }
}
