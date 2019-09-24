<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CuentaConsorcio extends Model
{
    protected $table = 'cuenta_consorcio';
    
    protected function listar_formulario()
    {
        $listar = DB::select("SELECT 
                                s.id,
                                s.rut,
                                concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                case when s.fecha_egreso is null then 'vigente'
                                else concat('egresado (',to_char(s.fecha_egreso,'dd-mm-yyyy'),')')
                                end AS estado_socio,
                                
                                case when ec.descripcion is null then 'Sin Registro'
                                else ec.descripcion
                                end AS pago_actual_ds,
                                concat(m.descripcion,' ',a.descripcion) as fecha_ds,
                                
                                case when ecx.descripcion is null then 'Sin Registro'
                                else ecx.descripcion
                                end AS pago_actual_cex,
                                concat(mx.descripcion,' ',ax.descripcion) as fecha_cex
                                
                                
                                from socios s
                                left join estado_consorcio_dia_sueldo ec on ec.socio_id = s.id
                                left join estado_consorcio_cuota_extra ecx on ecx.socio_id = s.id
                                left join mes m on m.id = ec.mes_id
                                left join anio a on a.id = ec.anio_id
                                left join mes mx on m.id = ecx.mes_id
                                left join anio ax on a.id = ecx.anio_id
                                order by s.a_paterno, s.a_materno");

        if (count($listar) > 0) {
    		return $listar;
    	}else{
    		return '';
    	}
    }

    protected function filtrar($search)
    {
    	$busca = mb_strtolower($search);

    	$filtro = DB::select("SELECT 
                                s.id,
                                s.rut,
                                concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                case when s.fecha_egreso is null then 'vigente'
                                else concat('egresado (',to_char(s.fecha_egreso,'dd-mm-yyyy'),')')
                                end AS estado_socio,
                                
                                case when ec.descripcion is null then 'Sin Registro'
                                else ec.descripcion
                                end AS pago_actual_ds,
                                concat(m.descripcion,' ',a.descripcion) as fecha_ds,
                                
                                case when ecx.descripcion is null then 'Sin Registro'
                                else ecx.descripcion
                                end AS pago_actual_cex,
                                concat(mx.descripcion,' ',ax.descripcion) as fecha_cex
                                
                                
                                from socios s
                                left join estado_consorcio_dia_sueldo ec on ec.socio_id = s.id
                                left join estado_consorcio_cuota_extra ecx on ecx.socio_id = s.id
                                left join mes m on m.id = ec.mes_id
                                left join anio a on a.id = ec.anio_id
                                left join mes mx on m.id = ecx.mes_id
                                left join anio ax on a.id = ecx.anio_id
                                where lower(CONCAT(nombres,' ',a_paterno,' ',a_materno)) like  '%$busca%'
                                order by s.a_paterno, s.a_materno
                       ");
                        
        if (count($filtro) > 0) {
    		return $filtro;
    	}else{
    		return '';
    	}
    	//return $filtro;
    }

    protected function insertar_ds($r)
    {
      $verif = $this->where([
        'anio_id' =>  $r->anio_id,
        'socio_id' => $r->socio_id
      ])->first();

      if ($verif) {

        switch ((string)$r->mes_id) {
            case '1':  $verif->monto_mes_ds_1 = $r->monto;  break;
            case '2':  $verif->monto_mes_ds_2 = $r->monto;  break;
            case '3':  $verif->monto_mes_ds_3 = $r->monto;  break;
            case '4':  $verif->monto_mes_ds_4 = $r->monto;  break;
            case '5':  $verif->monto_mes_ds_5 = $r->monto;  break;
            case '6':  $verif->monto_mes_ds_6 = $r->monto;  break;
            case '7':  $verif->monto_mes_ds_7 = $r->monto;  break;
            case '8':  $verif->monto_mes_ds_8 = $r->monto;  break;
            case '9':  $verif->monto_mes_ds_9 = $r->monto;  break;
            case '10': $verif->monto_mes_ds_10 = $r->monto;  break;
            case '11': $verif->monto_mes_ds_11 = $r->monto;  break;
            case '12': $verif->monto_mes_ds_12 = $r->monto;  break;
            default: break;
          }

          if ($verif->save()) {
              return ['estado'=>'success', 'mensaje'=>'Día de sueldo actualizado'];
          }
          return ['estado'=>'failed', 'mensaje'=>'Error al actualizar día de sueldo'];
            

      }else{
          $ds = $this;
          $ds->socio_id = $r->socio_id;
          $ds->vinculado = 'S';
          $ds->anio_id = $r->anio_id;
        
          switch ((string)$r->mes_id) {
            case '1':  $ds->monto_mes_ds_1 = $r->monto;  break;
            case '2':  $ds->monto_mes_ds_2 = $r->monto;  break;
            case '3':  $ds->monto_mes_ds_3 = $r->monto;  break;
            case '4':  $ds->monto_mes_ds_4 = $r->monto;  break;
            case '5':  $ds->monto_mes_ds_5 = $r->monto;  break;
            case '6':  $ds->monto_mes_ds_6 = $r->monto;  break;
            case '7':  $ds->monto_mes_ds_7 = $r->monto;  break;
            case '8':  $ds->monto_mes_ds_8 = $r->monto;  break;
            case '9':  $ds->monto_mes_ds_9 = $r->monto;  break;
            case '10': $ds->monto_mes_ds_10 = $r->monto;  break;
            case '11': $ds->monto_mes_ds_11 = $r->monto;  break;
            case '12': $ds->monto_mes_ds_12 = $r->monto;  break;
            default: break;
          }

          if ($ds->save()) {
              return ['estado'=>'success', 'mensaje'=>'Día de sueldo ingresado'];
          }
          return ['estado'=>'failed', 'mensaje'=>'Error al ingresar día de sueldo'];
      }
      dd("dfsd");

    }
    protected function insertar_cex($r)
    {
      
    }
}
