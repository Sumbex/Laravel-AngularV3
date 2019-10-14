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


                              order by 
                                (case when fecha_egreso is null then 'vigente'
                                else concat('egresado (',to_char(fecha_egreso,'dd-mm-yyyy'),')')
                                end) asc, s.a_paterno, s.a_materno
                              
                              ) AS x
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
                return ['estado'=>'success', 'mensaje'=>'Día de sueldo actualizado'];
              }
              return ['estado'=>'failed', 'mensaje'=>'Se ha guardado día de sueldo pero no se ha reecalculado el estado'];
              
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
                return ['estado'=>'success', 'mensaje'=>'Día de sueldo actualizado'];
              }
              return ['estado'=>'failed', 'mensaje'=>'Se ha guardado día de sueldo pero no se ha reecalculado el estado'];
              
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
          return ['estado'=>'failed', 'mensaje'=>'Error al ingresar cuota extraordinaria'];
      }
    }
    protected function insertar_cex_desde_table($r)
    {
        $cc_id ='';
        $mes ='';
        $tipo_pago = '2';
        $tipo_retiro ='';
        $porcentaje='';
        $monto='';

       $verif = $this->find($r->cc_id);

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
          return ['estado'=>'failed', 'mensaje'=>'Error al ingresar cuota extraordinaria'];
      }
    }
    

    protected function tabla_consorcio($anio_id)//CUENTA CONSORCIO
    {
        $listar = DB::select("SELECT 
                                s.id socio_id,
                                cc.id cuenta_consorcio_id,
                                cc.vinculado,
                                concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                COALESCE(monto_mes_ds_1,0) monto_mes_ds_1,
                                COALESCE(monto_mes_cex_1,0) monto_mes_cex_1,
                                COALESCE(monto_mes_ds_2,0) monto_mes_ds_2,
                                COALESCE(monto_mes_cex_2,0) monto_mes_cex_2,
                                COALESCE(monto_mes_ds_3,0) monto_mes_ds_3,
                                COALESCE(monto_mes_cex_3,0) monto_mes_cex_3,
                                COALESCE(monto_mes_ds_4,0) monto_mes_ds_4,
                                COALESCE(monto_mes_cex_4,0) monto_mes_cex_4,
                                COALESCE(monto_mes_ds_5,0) monto_mes_ds_5,
                                COALESCE(monto_mes_cex_5,0) monto_mes_cex_5,
                                COALESCE(monto_mes_ds_6,0) monto_mes_ds_6,
                                COALESCE(monto_mes_cex_6,0) monto_mes_cex_6,
                                COALESCE(monto_mes_ds_7,0) monto_mes_ds_7,
                                COALESCE(monto_mes_cex_7,0) monto_mes_cex_7,
                                COALESCE(monto_mes_ds_8,0) monto_mes_ds_8,
                                COALESCE(monto_mes_cex_8,0) monto_mes_cex_8,
                                COALESCE(monto_mes_ds_9,0) monto_mes_ds_9,
                                COALESCE(monto_mes_cex_9,0) monto_mes_cex_9,
                                COALESCE(monto_mes_ds_10,0) monto_mes_ds_10,
                                COALESCE(monto_mes_cex_10,0) monto_mes_cex_10,
                                COALESCE(monto_mes_ds_11,0) monto_mes_ds_11,
                                COALESCE(monto_mes_cex_11,0) monto_mes_cex_11,
                                COALESCE(monto_mes_ds_12,0) monto_mes_ds_12,
                                COALESCE(monto_mes_cex_12,0) monto_mes_cex_12,
                                
                                (
                                  COALESCE(monto_mes_ds_1,0) +
                                  COALESCE(monto_mes_ds_2,0) +
                                  COALESCE(monto_mes_ds_3,0) +
                                  COALESCE(monto_mes_ds_4,0) +
                                  COALESCE(monto_mes_ds_5,0) +
                                  COALESCE(monto_mes_ds_6,0) +
                                  COALESCE(monto_mes_ds_7,0) +
                                  COALESCE(monto_mes_ds_8,0) +
                                  COALESCE(monto_mes_ds_9,0)+
                                  COALESCE(monto_mes_ds_10,0) +
                                  COALESCE(monto_mes_ds_11,0) +
                                  COALESCE(monto_mes_ds_12,0) 
                                ) monto_total_ds_socio,
                                
                                (
                                  COALESCE(monto_mes_cex_1,0) +
                                  COALESCE(monto_mes_cex_2,0) +
                                  COALESCE(monto_mes_cex_3,0) +
                                  COALESCE(monto_mes_cex_4,0) +
                                  COALESCE(monto_mes_cex_5,0) +
                                  COALESCE(monto_mes_cex_6,0) +
                                  COALESCE(monto_mes_cex_7,0) +
                                  COALESCE(monto_mes_cex_8,0) +
                                  COALESCE(monto_mes_cex_9,0)+ 
                                  COALESCE(monto_mes_cex_10,0) +
                                  COALESCE(monto_mes_cex_11,0) +
                                  COALESCE(monto_mes_cex_12,0) 
                                ) monto_total_cex_socio,
                                (
                                  COALESCE(monto_mes_ds_1,0) -
                                  COALESCE(monto_mes_cex_1,0) +
                                  COALESCE(monto_mes_ds_2,0) -
                                  COALESCE(monto_mes_cex_2,0) +
                                  COALESCE(monto_mes_ds_3,0) -
                                  COALESCE(monto_mes_cex_3,0) +
                                  COALESCE(monto_mes_ds_4,0) -
                                  COALESCE(monto_mes_cex_4,0) +
                                  COALESCE(monto_mes_ds_5,0) -
                                  COALESCE(monto_mes_cex_5,0) +
                                  COALESCE(monto_mes_ds_6,0) -
                                  COALESCE(monto_mes_cex_6,0) +
                                  COALESCE(monto_mes_ds_7,0) -
                                  COALESCE(monto_mes_cex_7,0) +
                                  COALESCE(monto_mes_ds_8,0) -
                                  COALESCE(monto_mes_cex_8,0) +
                                  COALESCE(monto_mes_ds_9,0)-
                                  COALESCE(monto_mes_cex_9,0)+ 
                                  COALESCE(monto_mes_ds_10,0) -
                                  COALESCE(monto_mes_cex_10,0) +
                                  COALESCE(monto_mes_ds_11,0) -
                                  COALESCE(monto_mes_cex_11,0) +
                                  COALESCE(monto_mes_ds_12,0) -
                                  COALESCE(monto_mes_cex_12,0) 
                                ) monto_total_socio,
                                s.retiro_pago_beneficio pb
                                                        
                            from cuenta_consorcio cc
                            inner join socios s on s.id = cc.socio_id 
                            where cc.anio_id = $anio_id
                            order by a_paterno, a_materno asc
                            ");

        if (count($listar) > 0) {
    		return $listar;
    	}else{
    		return '';
    	}
    }
    protected function filtrar_tabla_consorcio($anio_id, $search)
    {
       $busca = mb_strtolower($search);
       
       $listar = DB::select("SELECT 
                                s.id socio_id,
                                cc.id cuenta_consorcio_id,
                                cc.vinculado,
                                concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                COALESCE(monto_mes_ds_1,0) monto_mes_ds_1,
                                COALESCE(monto_mes_cex_1,0) monto_mes_cex_1,
                                COALESCE(monto_mes_ds_2,0) monto_mes_ds_2,
                                COALESCE(monto_mes_cex_2,0) monto_mes_cex_2,
                                COALESCE(monto_mes_ds_3,0) monto_mes_ds_3,
                                COALESCE(monto_mes_cex_3,0) monto_mes_cex_3,
                                COALESCE(monto_mes_ds_4,0) monto_mes_ds_4,
                                COALESCE(monto_mes_cex_4,0) monto_mes_cex_4,
                                COALESCE(monto_mes_ds_5,0) monto_mes_ds_5,
                                COALESCE(monto_mes_cex_5,0) monto_mes_cex_5,
                                COALESCE(monto_mes_ds_6,0) monto_mes_ds_6,
                                COALESCE(monto_mes_cex_6,0) monto_mes_cex_6,
                                COALESCE(monto_mes_ds_7,0) monto_mes_ds_7,
                                COALESCE(monto_mes_cex_7,0) monto_mes_cex_7,
                                COALESCE(monto_mes_ds_8,0) monto_mes_ds_8,
                                COALESCE(monto_mes_cex_8,0) monto_mes_cex_8,
                                COALESCE(monto_mes_ds_9,0) monto_mes_ds_9,
                                COALESCE(monto_mes_cex_9,0) monto_mes_cex_9,
                                COALESCE(monto_mes_ds_10,0) monto_mes_ds_10,
                                COALESCE(monto_mes_cex_10,0) monto_mes_cex_10,
                                COALESCE(monto_mes_ds_11,0) monto_mes_ds_11,
                                COALESCE(monto_mes_cex_11,0) monto_mes_cex_11,
                                COALESCE(monto_mes_ds_12,0) monto_mes_ds_12,
                                COALESCE(monto_mes_cex_12,0) monto_mes_cex_12,
                                
                                (
                                  COALESCE(monto_mes_ds_1,0) +
                                  COALESCE(monto_mes_ds_2,0) +
                                  COALESCE(monto_mes_ds_3,0) +
                                  COALESCE(monto_mes_ds_4,0) +
                                  COALESCE(monto_mes_ds_5,0) +
                                  COALESCE(monto_mes_ds_6,0) +
                                  COALESCE(monto_mes_ds_7,0) +
                                  COALESCE(monto_mes_ds_8,0) +
                                  COALESCE(monto_mes_ds_9,0)+
                                  COALESCE(monto_mes_ds_10,0) +
                                  COALESCE(monto_mes_ds_11,0) +
                                  COALESCE(monto_mes_ds_12,0) 
                                ) monto_total_ds_socio,
                                
                                (
                                  COALESCE(monto_mes_cex_1,0) +
                                  COALESCE(monto_mes_cex_2,0) +
                                  COALESCE(monto_mes_cex_3,0) +
                                  COALESCE(monto_mes_cex_4,0) +
                                  COALESCE(monto_mes_cex_5,0) +
                                  COALESCE(monto_mes_cex_6,0) +
                                  COALESCE(monto_mes_cex_7,0) +
                                  COALESCE(monto_mes_cex_8,0) +
                                  COALESCE(monto_mes_cex_9,0)+ 
                                  COALESCE(monto_mes_cex_10,0) +
                                  COALESCE(monto_mes_cex_11,0) +
                                  COALESCE(monto_mes_cex_12,0) 
                                ) monto_total_cex_socio,
                                (
                                  COALESCE(monto_mes_ds_1,0) -
                                  COALESCE(monto_mes_cex_1,0) +
                                  COALESCE(monto_mes_ds_2,0) -
                                  COALESCE(monto_mes_cex_2,0) +
                                  COALESCE(monto_mes_ds_3,0) -
                                  COALESCE(monto_mes_cex_3,0) +
                                  COALESCE(monto_mes_ds_4,0) -
                                  COALESCE(monto_mes_cex_4,0) +
                                  COALESCE(monto_mes_ds_5,0) -
                                  COALESCE(monto_mes_cex_5,0) +
                                  COALESCE(monto_mes_ds_6,0) -
                                  COALESCE(monto_mes_cex_6,0) +
                                  COALESCE(monto_mes_ds_7,0) -
                                  COALESCE(monto_mes_cex_7,0) +
                                  COALESCE(monto_mes_ds_8,0) -
                                  COALESCE(monto_mes_cex_8,0) +
                                  COALESCE(monto_mes_ds_9,0)-
                                  COALESCE(monto_mes_cex_9,0)+ 
                                  COALESCE(monto_mes_ds_10,0) -
                                  COALESCE(monto_mes_cex_10,0) +
                                  COALESCE(monto_mes_ds_11,0) -
                                  COALESCE(monto_mes_cex_11,0) +
                                  COALESCE(monto_mes_ds_12,0) -
                                  COALESCE(monto_mes_cex_12,0) 
                                ) monto_total_socio,
                                s.retiro_pago_beneficio pb
                                                        
                            from cuenta_consorcio cc
                            inner join socios s on s.id = cc.socio_id 
                            where cc.anio_id = $anio_id
                            and lower(CONCAT(nombres,' ',a_paterno,' ',a_materno)) like  '%$busca%'
                            ");

        if (count($listar) > 0) {
    		return $listar;
    	}else{
    		return '';
    	}
    }
    protected function totales_mensuales_anual($anio_id)//ESTA QUERY TIENE LOS TOTALES MENSUALES(12M) DE LOS SOCIOS (SEGUN AÑO)
    {
        $listar = DB::select("SELECT
                                SUM(COALESCE(monto_mes_ds_1,0)) monto_mes_ds_1,
                                SUM(COALESCE(monto_mes_cex_1,0)) monto_mes_cex_1,
                                SUM(COALESCE(monto_mes_ds_2,0)) monto_mes_ds_2,
                                SUM(COALESCE(monto_mes_cex_2,0)) monto_mes_cex_2,
                                SUM(COALESCE(monto_mes_ds_3,0)) monto_mes_ds_3,
                                SUM(COALESCE(monto_mes_cex_3,0)) monto_mes_cex_3,
                                SUM(COALESCE(monto_mes_ds_4,0)) monto_mes_ds_4,
                                SUM(COALESCE(monto_mes_cex_4,0)) monto_mes_cex_4,
                                SUM(COALESCE(monto_mes_ds_5,0)) monto_mes_ds_5,
                                SUM(COALESCE(monto_mes_cex_5,0)) monto_mes_cex_5,
                                SUM(COALESCE(monto_mes_ds_6,0)) monto_mes_ds_6,
                                SUM(COALESCE(monto_mes_cex_6,0)) monto_mes_cex_6,
                                SUM(COALESCE(monto_mes_ds_7,0)) monto_mes_ds_7,
                                SUM(COALESCE(monto_mes_cex_7,0)) monto_mes_cex_7,
                                SUM(COALESCE(monto_mes_ds_8,0)) monto_mes_ds_8,
                                SUM(COALESCE(monto_mes_cex_8,0)) monto_mes_cex_8,
                                SUM(COALESCE(monto_mes_ds_9,0)) monto_mes_ds_9,
                                SUM(COALESCE(monto_mes_cex_9,0)) monto_mes_cex_9,
                                SUM(COALESCE(monto_mes_ds_10,0)) monto_mes_ds_10,
                                SUM(COALESCE(monto_mes_cex_10,0)) monto_mes_cex_10,
                                SUM(COALESCE(monto_mes_ds_11,0)) monto_mes_ds_11,
                                SUM(COALESCE(monto_mes_cex_11,0)) monto_mes_cex_11,
                                SUM(COALESCE(monto_mes_ds_12,0)) monto_mes_ds_12,
                                SUM(COALESCE(monto_mes_cex_12,0)) monto_mes_cex_12,
                                
                                (
                                SUM(COALESCE(monto_mes_ds_1,0)) -
                                SUM(COALESCE(monto_mes_cex_1,0)) +
                                SUM(COALESCE(monto_mes_ds_2,0)) -
                                SUM(COALESCE(monto_mes_cex_2,0)) +
                                SUM(COALESCE(monto_mes_ds_3,0)) -
                                SUM(COALESCE(monto_mes_cex_3,0)) +
                                SUM(COALESCE(monto_mes_ds_4,0)) -
                                SUM(COALESCE(monto_mes_cex_4,0)) +
                                SUM(COALESCE(monto_mes_ds_5,0)) -
                                SUM(COALESCE(monto_mes_cex_5,0)) +
                                SUM(COALESCE(monto_mes_ds_6,0)) -
                                SUM(COALESCE(monto_mes_cex_6,0)) +
                                SUM(COALESCE(monto_mes_ds_7,0)) -
                                SUM(COALESCE(monto_mes_cex_7,0)) +
                                SUM(COALESCE(monto_mes_ds_8,0)) -
                                SUM(COALESCE(monto_mes_cex_8,0)) +
                                SUM(COALESCE(monto_mes_ds_9,0)) -
                                SUM(COALESCE(monto_mes_cex_9,0)) +
                                SUM(COALESCE(monto_mes_ds_10,0)) -
                                SUM(COALESCE(monto_mes_cex_10,0)) +
                                SUM(COALESCE(monto_mes_ds_11,0)) -
                                SUM(COALESCE(monto_mes_cex_11,0)) +
                                SUM(COALESCE(monto_mes_ds_12,0)) -
                                SUM(COALESCE(monto_mes_cex_12,0)) 
                                ) SUMA_ANUAL
                                
                            from cuenta_consorcio where anio_id = $anio_id");

        if (count($listar) > 0) {
    		return response()->json($listar[0]);
    	}else{
    		return '';
    	}
    }

    protected function total_anual_socio($anio_id)
    {
        $listar = DB::select("SELECT
                              sum(X.monto_total_ds_socio) monto_total_ds_socio,
                              sum(X.monto_total_cex_socio) monto_total_cex_socio,
                              (sum(X.monto_total_ds_socio) - sum(X.monto_total_cex_socio)) monto_total_socio
                              from
                              ( select 
                                  s.id socio_id,
                                  cc.id cuenta_consorcio_id,
                                  concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                  (
                                      COALESCE(monto_mes_ds_1,0) +
                                      COALESCE(monto_mes_ds_2,0) +
                                      COALESCE(monto_mes_ds_3,0) +
                                      COALESCE(monto_mes_ds_4,0) +
                                      COALESCE(monto_mes_ds_5,0) +
                                      COALESCE(monto_mes_ds_6,0) +
                                      COALESCE(monto_mes_ds_7,0) +
                                      COALESCE(monto_mes_ds_8,0) +
                                      COALESCE(monto_mes_ds_9,0)+
                                      COALESCE(monto_mes_ds_10,0) +
                                      COALESCE(monto_mes_ds_11,0) +
                                      COALESCE(monto_mes_ds_12,0) 
                                  ) monto_total_ds_socio,
                                  (
                                      COALESCE(monto_mes_cex_1,0) +
                                      COALESCE(monto_mes_cex_2,0) +
                                      COALESCE(monto_mes_cex_3,0) +
                                      COALESCE(monto_mes_cex_4,0) +
                                      COALESCE(monto_mes_cex_5,0) +
                                      COALESCE(monto_mes_cex_6,0) +
                                      COALESCE(monto_mes_cex_7,0) +
                                      COALESCE(monto_mes_cex_8,0) +
                                      COALESCE(monto_mes_cex_9,0)+ 
                                      COALESCE(monto_mes_cex_10,0) +
                                      COALESCE(monto_mes_cex_11,0) +
                                      COALESCE(monto_mes_cex_12,0) 
                                  ) monto_total_cex_socio
                                  
                                  from cuenta_consorcio cc
                                  inner join socios s on s.id = cc.socio_id 
                                  where cc.anio_id = $anio_id
                              ) x");
         if (count($listar) > 0) {
    		    return response()->json($listar[0]);
          }else{
            return '';
          }
    }

    // TABLA DE DESVINCULADOS

    protected function tabla_desvinculados($anio_id)
    {
        $listar = DB::select("SELECT 
                                s.id socio_id,
                                cc.id cuenta_consorcio_id,
                                cc.vinculado,
                                concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                COALESCE(monto_mes_ds_1,0) monto_mes_ds_1,
                                COALESCE(monto_mes_cex_1,0) monto_mes_cex_1,
                                COALESCE(monto_mes_ds_2,0) monto_mes_ds_2,
                                COALESCE(monto_mes_cex_2,0) monto_mes_cex_2,
                                COALESCE(monto_mes_ds_3,0) monto_mes_ds_3,
                                COALESCE(monto_mes_cex_3,0) monto_mes_cex_3,
                                COALESCE(monto_mes_ds_4,0) monto_mes_ds_4,
                                COALESCE(monto_mes_cex_4,0) monto_mes_cex_4,
                                COALESCE(monto_mes_ds_5,0) monto_mes_ds_5,
                                COALESCE(monto_mes_cex_5,0) monto_mes_cex_5,
                                COALESCE(monto_mes_ds_6,0) monto_mes_ds_6,
                                COALESCE(monto_mes_cex_6,0) monto_mes_cex_6,
                                COALESCE(monto_mes_ds_7,0) monto_mes_ds_7,
                                COALESCE(monto_mes_cex_7,0) monto_mes_cex_7,
                                COALESCE(monto_mes_ds_8,0) monto_mes_ds_8,
                                COALESCE(monto_mes_cex_8,0) monto_mes_cex_8,
                                COALESCE(monto_mes_ds_9,0) monto_mes_ds_9,
                                COALESCE(monto_mes_cex_9,0) monto_mes_cex_9,
                                COALESCE(monto_mes_ds_10,0) monto_mes_ds_10,
                                COALESCE(monto_mes_cex_10,0) monto_mes_cex_10,
                                COALESCE(monto_mes_ds_11,0) monto_mes_ds_11,
                                COALESCE(monto_mes_cex_11,0) monto_mes_cex_11,
                                COALESCE(monto_mes_ds_12,0) monto_mes_ds_12,
                                COALESCE(monto_mes_cex_12,0) monto_mes_cex_12,
                                
                                (
                                  COALESCE(monto_mes_ds_1,0) +
                                  COALESCE(monto_mes_ds_2,0) +
                                  COALESCE(monto_mes_ds_3,0) +
                                  COALESCE(monto_mes_ds_4,0) +
                                  COALESCE(monto_mes_ds_5,0) +
                                  COALESCE(monto_mes_ds_6,0) +
                                  COALESCE(monto_mes_ds_7,0) +
                                  COALESCE(monto_mes_ds_8,0) +
                                  COALESCE(monto_mes_ds_9,0)+
                                  COALESCE(monto_mes_ds_10,0) +
                                  COALESCE(monto_mes_ds_11,0) +
                                  COALESCE(monto_mes_ds_12,0) 
                                ) monto_total_ds_socio,
                                
                                (
                                  COALESCE(monto_mes_cex_1,0) +
                                  COALESCE(monto_mes_cex_2,0) +
                                  COALESCE(monto_mes_cex_3,0) +
                                  COALESCE(monto_mes_cex_4,0) +
                                  COALESCE(monto_mes_cex_5,0) +
                                  COALESCE(monto_mes_cex_6,0) +
                                  COALESCE(monto_mes_cex_7,0) +
                                  COALESCE(monto_mes_cex_8,0) +
                                  COALESCE(monto_mes_cex_9,0)+ 
                                  COALESCE(monto_mes_cex_10,0) +
                                  COALESCE(monto_mes_cex_11,0) +
                                  COALESCE(monto_mes_cex_12,0) 
                                ) monto_total_cex_socio,
                                (
                                  COALESCE(monto_mes_ds_1,0) +
                                  COALESCE(monto_mes_cex_1,0) +
                                  COALESCE(monto_mes_ds_2,0) +
                                  COALESCE(monto_mes_cex_2,0) +
                                  COALESCE(monto_mes_ds_3,0) +
                                  COALESCE(monto_mes_cex_3,0) +
                                  COALESCE(monto_mes_ds_4,0) +
                                  COALESCE(monto_mes_cex_4,0) +
                                  COALESCE(monto_mes_ds_5,0) +
                                  COALESCE(monto_mes_cex_5,0) +
                                  COALESCE(monto_mes_ds_6,0) +
                                  COALESCE(monto_mes_cex_6,0) +
                                  COALESCE(monto_mes_ds_7,0) +
                                  COALESCE(monto_mes_cex_7,0) +
                                  COALESCE(monto_mes_ds_8,0) +
                                  COALESCE(monto_mes_cex_8,0) +
                                  COALESCE(monto_mes_ds_9,0)+
                                  COALESCE(monto_mes_cex_9,0)+ 
                                  COALESCE(monto_mes_ds_10,0) +
                                  COALESCE(monto_mes_cex_10,0) +
                                  COALESCE(monto_mes_ds_11,0) +
                                  COALESCE(monto_mes_cex_11,0) +
                                  COALESCE(monto_mes_ds_12,0) +
                                  COALESCE(monto_mes_cex_12,0) 
                                ) monto_total_socio
                                                        
                            from cuenta_consorcio cc
                            inner join socios s on s.id = cc.socio_id 
                            where cc.anio_id = $anio_id and cc.vinculado = 'N'");

        if (count($listar) > 0) {
    		    return $listar;
        }else{
            return '';
        }
    }

    protected function calcular_dia_sueldo($socio_id)
    {
        $ultimo = DB::select("select max(id) id from cuenta_consorcio where socio_id = $socio_id");
        if (count($ultimo) > 0) {
          
             $ultimo_id_socio = $ultimo[0];
            $ultimo = $ultimo_id_socio->id;
            
            $ds = DB::select("SELECT 
                                  COALESCE(
                                      x.monto_mes_ds_12,
                                      x.monto_mes_ds_11,
                                      x.monto_mes_ds_10,
                                      x.monto_mes_ds_9,
                                      x.monto_mes_ds_8,
                                      x.monto_mes_ds_7,
                                      x.monto_mes_ds_6,
                                      x.monto_mes_ds_5,
                                      x.monto_mes_ds_4,
                                      x.monto_mes_ds_3,
                                      x.monto_mes_ds_2,
                                      x.monto_mes_ds_1    
                                  ) dia_sueldo,

                                  COALESCE(
                                      x.monto_mes_cex_12,
                                      x.monto_mes_cex_11,
                                      x.monto_mes_cex_10,
                                      x.monto_mes_cex_9,
                                      x.monto_mes_cex_8,
                                      x.monto_mes_cex_7,
                                      x.monto_mes_cex_6,
                                      x.monto_mes_cex_5,
                                      x.monto_mes_cex_4,
                                      x.monto_mes_cex_3,
                                      x.monto_mes_cex_2,
                                      x.monto_mes_cex_1    
                                  ) pago_beneficio
                              from

                              (select 
                                  
                                case when  (COALESCE(monto_mes_ds_12, 0)) = 0 then null else monto_mes_ds_12 end monto_mes_ds_12,
                                case when  (COALESCE(monto_mes_ds_11,0) ) = 0 then null else monto_mes_ds_11 end monto_mes_ds_11,
                                case when  (COALESCE(monto_mes_ds_10,0) ) = 0 then null else monto_mes_ds_10 end monto_mes_ds_10,
                                case when  (COALESCE(monto_mes_ds_9,0) )  = 0 then null else monto_mes_ds_9 end monto_mes_ds_9,
                                case when  (COALESCE(monto_mes_ds_8,0) )  = 0 then null else monto_mes_ds_8 end monto_mes_ds_8,
                                case when  (COALESCE(monto_mes_ds_7,0) ) = 0 then null else monto_mes_ds_7 end monto_mes_ds_7,
                                case when  (COALESCE(monto_mes_ds_6,0) ) = 0 then null else monto_mes_ds_6 end monto_mes_ds_6,
                                case when  (COALESCE(monto_mes_ds_5,0) ) = 0 then null else monto_mes_ds_5 end monto_mes_ds_5,
                                case when  (COALESCE(monto_mes_ds_4,0) ) = 0 then null else monto_mes_ds_4 end monto_mes_ds_4,
                                case when  (COALESCE(monto_mes_ds_3,0) ) = 0 then null else monto_mes_ds_3 end monto_mes_ds_3,
                                case when  (COALESCE(monto_mes_ds_2,0) ) = 0 then null else monto_mes_ds_2 end monto_mes_ds_2,
                                case when  (COALESCE(monto_mes_ds_1,0) ) = 0 then null else monto_mes_ds_1 end monto_mes_ds_1,

                                case when  (COALESCE(monto_mes_cex_12, 0)) = 0 then null else monto_mes_cex_12 end monto_mes_cex_12,
                                case when  (COALESCE(monto_mes_cex_11,0) ) = 0 then null else monto_mes_cex_11 end monto_mes_cex_11,
                                case when  (COALESCE(monto_mes_cex_10,0) ) = 0 then null else monto_mes_cex_10 end monto_mes_cex_10,
                                case when  (COALESCE(monto_mes_cex_9,0) )  = 0 then null else monto_mes_cex_9 end monto_mes_cex_9,
                                case when  (COALESCE(monto_mes_cex_8,0) )  = 0 then null else monto_mes_cex_8 end monto_mes_cex_8,
                                case when  (COALESCE(monto_mes_cex_7,0) ) = 0 then null else monto_mes_cex_7 end monto_mes_cex_7,
                                case when  (COALESCE(monto_mes_cex_6,0) ) = 0 then null else monto_mes_cex_6 end monto_mes_cex_6,
                                case when  (COALESCE(monto_mes_cex_5,0) ) = 0 then null else monto_mes_cex_5 end monto_mes_cex_5,
                                case when  (COALESCE(monto_mes_cex_4,0) ) = 0 then null else monto_mes_cex_4 end monto_mes_cex_4,
                                case when  (COALESCE(monto_mes_cex_3,0) ) = 0 then null else monto_mes_cex_3 end monto_mes_cex_3,
                                case when  (COALESCE(monto_mes_cex_2,0) ) = 0 then null else monto_mes_cex_2 end monto_mes_cex_2,
                                case when  (COALESCE(monto_mes_cex_1,0) ) = 0 then null else monto_mes_cex_1 end monto_mes_cex_1
                              
                              from cuenta_consorcio where id = $ultimo
                              and socio_id = $socio_id 
                              ) x");
              if (count($ds) > 0) {
                  return [
                          'cuenta-consorcio_id' => $ultimo,
                          'dia_sueldo' => $ds[0]->dia_sueldo,
                          'pago_beneficio' => $ds[0]->pago_beneficio
                  ];
              }
              return '';


        }else{
            return '';
        }

    }

    protected function listar_pago_beneficios($anio)
    {
      $listar = DB::select("SELECT 
                    socio_id,
                    CONCAT(m.descripcion,' ',a.descripcion) as fecha,
                    CONCAT(nombres,' ',a_paterno,' ',a_materno) nombre,
                    pb.descripcion,
                    CONCAT(CAST (porcentaje AS float) *100,'%') porcentaje,
                    monto_dia_sueldo as monto_v
                from cc_pago_beneficios pb
                inner join socios s on s.id = pb.socio_id
                inner join mes m on m.id = pb.mes_id
                inner join anio a on a.id = pb.anio_id
                where pb.anio_id = $anio and s.retiro_pago_beneficio = 'S'");

      if (count($listar) > 0) {
    		    return $listar;
        }else{
            return '';
        }
    }

    protected function desvincular_sumar_totales($anio, $socio)
    {
        $listar = DB::select("SELECT 
                                    socio_id,
                                    monto_total_ds_socio, 
                                    monto_total_cex_socio,
                                    (monto_total_ds_socio - monto_total_cex_socio) monto_h
                            from (SELECT 
                                s.id socio_id,
                                cc.id cuenta_consorcio_id,
                                cc.vinculado,
                                concat(nombres,' ',a_paterno,' ',a_materno) nombre,
                                COALESCE(monto_mes_ds_1,0) monto_mes_ds_1,
                                COALESCE(monto_mes_cex_1,0) monto_mes_cex_1,
                                COALESCE(monto_mes_ds_2,0) monto_mes_ds_2,
                                COALESCE(monto_mes_cex_2,0) monto_mes_cex_2,
                                COALESCE(monto_mes_ds_3,0) monto_mes_ds_3,
                                COALESCE(monto_mes_cex_3,0) monto_mes_cex_3,
                                COALESCE(monto_mes_ds_4,0) monto_mes_ds_4,
                                COALESCE(monto_mes_cex_4,0) monto_mes_cex_4,
                                COALESCE(monto_mes_ds_5,0) monto_mes_ds_5,
                                COALESCE(monto_mes_cex_5,0) monto_mes_cex_5,
                                COALESCE(monto_mes_ds_6,0) monto_mes_ds_6,
                                COALESCE(monto_mes_cex_6,0) monto_mes_cex_6,
                                COALESCE(monto_mes_ds_7,0) monto_mes_ds_7,
                                COALESCE(monto_mes_cex_7,0) monto_mes_cex_7,
                                COALESCE(monto_mes_ds_8,0) monto_mes_ds_8,
                                COALESCE(monto_mes_cex_8,0) monto_mes_cex_8,
                                COALESCE(monto_mes_ds_9,0) monto_mes_ds_9,
                                COALESCE(monto_mes_cex_9,0) monto_mes_cex_9,
                                COALESCE(monto_mes_ds_10,0) monto_mes_ds_10,
                                COALESCE(monto_mes_cex_10,0) monto_mes_cex_10,
                                COALESCE(monto_mes_ds_11,0) monto_mes_ds_11,
                                COALESCE(monto_mes_cex_11,0) monto_mes_cex_11,
                                COALESCE(monto_mes_ds_12,0) monto_mes_ds_12,
                                COALESCE(monto_mes_cex_12,0) monto_mes_cex_12,
                                
                                (
                                  COALESCE(monto_mes_ds_1,0) +
                                  COALESCE(monto_mes_ds_2,0) +
                                  COALESCE(monto_mes_ds_3,0) +
                                  COALESCE(monto_mes_ds_4,0) +
                                  COALESCE(monto_mes_ds_5,0) +
                                  COALESCE(monto_mes_ds_6,0) +
                                  COALESCE(monto_mes_ds_7,0) +
                                  COALESCE(monto_mes_ds_8,0) +
                                  COALESCE(monto_mes_ds_9,0)+
                                  COALESCE(monto_mes_ds_10,0) +
                                  COALESCE(monto_mes_ds_11,0) +
                                  COALESCE(monto_mes_ds_12,0) 
                                ) monto_total_ds_socio,
                                
                                (
                                  COALESCE(monto_mes_cex_1,0) +
                                  COALESCE(monto_mes_cex_2,0) +
                                  COALESCE(monto_mes_cex_3,0) +
                                  COALESCE(monto_mes_cex_4,0) +
                                  COALESCE(monto_mes_cex_5,0) +
                                  COALESCE(monto_mes_cex_6,0) +
                                  COALESCE(monto_mes_cex_7,0) +
                                  COALESCE(monto_mes_cex_8,0) +
                                  COALESCE(monto_mes_cex_9,0)+ 
                                  COALESCE(monto_mes_cex_10,0) +
                                  COALESCE(monto_mes_cex_11,0) +
                                  COALESCE(monto_mes_cex_12,0) 
                                ) monto_total_cex_socio
                               
                                                        
                            from cuenta_consorcio cc
                            inner join socios s on s.id = cc.socio_id 
                            where cc.anio_id = $anio and socio_id = $socio and cc.vinculado = 'N') x
        ");

         if (count($listar) > 0) {
    		    return $listar[0];
          }else{
            return '';
          }
    }
}
