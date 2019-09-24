<?php

namespace App;

use App\EstadoConsorcioDs;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CuentaConsorcio extends Model
{
    protected $table = 'cuenta_consorcio';
    
    protected function listar_formulario()
    {
        $listar = DB::select("SELECT   
                                x.id,
                                x.rut,
                                x.nombre,
                                case when x.fecha_egreso is null then 'vigente'
                                else concat('egresado (',to_char(x.fecha_egreso,'dd-mm-yyyy'),')')
                                end AS estado_socio,
                                
                                case when x.descripcion_ds is null then 'Sin Registro'
                                else x.descripcion_ds
                                end AS pago_actual_ds,
                                concat(m_ds.descripcion,' ',a_ds.descripcion) as fecha_ds,
                                
                                case when descripcion_ecx is null then 'Sin Registro'
                                else descripcion_ecx
                                end AS pago_actual_cex,
                                concat(m_cex.descripcion,' ',a_cex.descripcion) as fecha_cex

                              from 
                              (select    
                                    s.id,
                                    s.rut,
                                    concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                    ec.descripcion as descripcion_ds,
                                    ec.mes_id as mes_ds_id,
                                    ec.anio_id as anio_ds_id,
                                    
                                    ecx.descripcion as descripcion_ecx,
                                    ecx.mes_id as mes_ecx_id,
                                    ecx.anio_id as anio_ecx_id,
                                    s.fecha_egreso
                                    
                                  from socios s
                                  left join estado_consorcio_dia_sueldo ec on ec.socio_id = s.id
                                  left join estado_consorcio_cuota_extra ecx on ecx.socio_id = s.id


                              order by s.a_paterno, s.a_materno) AS x
                              left join mes m_ds on m_ds.id = x.mes_ds_id
                              left join anio a_ds on a_ds.id = x.anio_ds_id
                              left join mes m_cex on m_cex.id = x.mes_ecx_id
                              left join anio a_cex on a_cex.id = x.anio_ecx_id");

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
                                x.id,
                                x.rut,
                                x.nombre,
                                case when x.fecha_egreso is null then 'vigente'
                                else concat('egresado (',to_char(x.fecha_egreso,'dd-mm-yyyy'),')')
                                end AS estado_socio,
                                
                                case when x.descripcion_ds is null then 'Sin Registro'
                                else x.descripcion_ds
                                end AS pago_actual_ds,
                                concat(m_ds.descripcion,' ',a_ds.descripcion) as fecha_ds,
                                
                                case when descripcion_ecx is null then 'Sin Registro'
                                else descripcion_ecx
                                end AS pago_actual_cex,
                                concat(m_cex.descripcion,' ',a_cex.descripcion) as fecha_cex

                              from 
                              (select    
                                    s.id,
                                    s.rut,
                                    concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                    ec.descripcion as descripcion_ds,
                                    ec.mes_id as mes_ds_id,
                                    ec.anio_id as anio_ds_id,
                                    
                                    ecx.descripcion as descripcion_ecx,
                                    ecx.mes_id as mes_ecx_id,
                                    ecx.anio_id as anio_ecx_id,
                                    s.fecha_egreso
                                    
                                  from socios s
                                  left join estado_consorcio_dia_sueldo ec on ec.socio_id = s.id
                                  left join estado_consorcio_cuota_extra ecx on ecx.socio_id = s.id

                              where lower(CONCAT(nombres,' ',a_paterno,' ',a_materno)) like  '%$busca%'
                              order by s.a_paterno, s.a_materno) AS x
                              left join mes m_ds on m_ds.id = x.mes_ds_id
                              left join anio a_ds on a_ds.id = x.anio_ds_id
                              left join mes m_cex on m_cex.id = x.mes_ecx_id
                              left join anio a_cex on a_cex.id = x.anio_ecx_id
                                
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

              $estado = EstadoConsorcioDs::insertar($r->socio_id, $r->mes_id, $r->anio_id);
              if ($estado) {
                return ['estado'=>'success', 'mensaje'=>'Cuota extraordinaria actualizada'];
              }
              return ['estado'=>'failed', 'mensaje'=>'Se ha guardado cuota extraordinaria pero no se ha reecalculado el estado'];
              
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

            $estado = EstadoConsorcioDs::insertar($r->socio_id, $r->mes_id, $r->anio_id);
              if ($estado) {
                return ['estado'=>'success', 'mensaje'=>'Cuota extraordinaria actualizada'];
              }
              return ['estado'=>'failed', 'mensaje'=>'Se ha guardado cuota extraordinaria pero no se ha reecalculado el estado'];
              
          }
          return ['estado'=>'failed', 'mensaje'=>'Error al ingresar día de sueldo'];
      }


    }
    protected function insertar_cex($r)
    {
       $verif = $this->where([
        'anio_id' =>  $r->anio_id,
        'socio_id' => $r->socio_id
      ])->first();

      if ($verif) {

        switch ((string)$r->mes_id) {
            case '1':  $verif->monto_mes_cex_1 = $r->monto;  break;
            case '2':  $verif->monto_mes_cex_2 = $r->monto;  break;
            case '3':  $verif->monto_mes_cex_3 = $r->monto;  break;
            case '4':  $verif->monto_mes_cex_4 = $r->monto;  break;
            case '5':  $verif->monto_mes_cex_5 = $r->monto;  break;
            case '6':  $verif->monto_mes_cex_6 = $r->monto;  break;
            case '7':  $verif->monto_mes_cex_7 = $r->monto;  break;
            case '8':  $verif->monto_mes_cex_8 = $r->monto;  break;
            case '9':  $verif->monto_mes_cex_9 = $r->monto;  break;
            case '10': $verif->monto_mes_cex_10 = $r->monto;  break;
            case '11': $verif->monto_mes_cex_11 = $r->monto;  break;
            case '12': $verif->monto_mes_cex_12 = $r->monto;  break;
            default: break;
          }

          if ($verif->save()) {
              $estado = EstadoConsorcioCex::insertar($r->socio_id, $r->mes_id, $r->anio_id);
              if ($estado) {
                return ['estado'=>'success', 'mensaje'=>'Cuota extraordinaria actualizada'];
              }
              return ['estado'=>'failed', 'mensaje'=>'Se ha guardado cuota extraordinaria pero no se ha reecalculado el estado'];
              
          }
          return ['estado'=>'failed', 'mensaje'=>'Error al actualizar cuota extraordinaria'];
            

      }else{
          $ds = $this;
          $ds->socio_id = $r->socio_id;
          $ds->vinculado = 'S';
          $ds->anio_id = $r->anio_id;
        
          switch ((string)$r->mes_id) {
            case '1':  $ds->monto_mes_cex_1 = $r->monto;  break;
            case '2':  $ds->monto_mes_cex_2 = $r->monto;  break;
            case '3':  $ds->monto_mes_cex_3 = $r->monto;  break;
            case '4':  $ds->monto_mes_cex_4 = $r->monto;  break;
            case '5':  $ds->monto_mes_cex_5 = $r->monto;  break;
            case '6':  $ds->monto_mes_cex_6 = $r->monto;  break;
            case '7':  $ds->monto_mes_cex_7 = $r->monto;  break;
            case '8':  $ds->monto_mes_cex_8 = $r->monto;  break;
            case '9':  $ds->monto_mes_cex_9 = $r->monto;  break;
            case '10': $ds->monto_mes_cex_10 = $r->monto;  break;
            case '11': $ds->monto_mes_cex_11 = $r->monto;  break;
            case '12': $ds->monto_mes_cex_12 = $r->monto;  break;
            default: break;
          }

          if ($ds->save()) {
              $estado = EstadoConsorcioCex::insertar($r->socio_id, $r->mes_id, $r->anio_id);
              if ($estado) {
                return ['estado'=>'success', 'mensaje'=>'Cuota extraordinaria actualizada'];
              }
              return ['estado'=>'failed', 'mensaje'=>'Se ha guardado cuota extraordinaria pero no se ha reecalculado el estado'];

          }
          return ['estado'=>'failed', 'mensaje'=>'Error al ingresar día de sueldo'];
      }
    }
}
