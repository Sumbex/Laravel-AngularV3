<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VacacionesSolicitud extends Model
{
    protected $table = 'vac_solicitud';

    protected function crear($r)
    {
       $verify = $this->validar_dias_basicos_mayor_a_dias_legales($r);
    //    $ultimo_dia_basico = VacDiasBasicosDevengados::where('vac_historial_id',$r->vacaciones_historial_id)->latest()->first();
    //    return $ultimo_dia_basico;
       if($verify['estado']){



            DB::beginTransaction();
           $crear = $this;
           $crear->vac_historial_id = $r->vacaciones_historial_id;
           $crear->fecha_solicitud = $r->fecha_solicitud;
           $crear->fecha_inicio = $r->fecha_inicio;
           $crear->fecha_termino = $r->fecha_termino;
           $crear->dias_legales = $r->dias_legales;
           $crear->dias_progresivos = $r->dias_progresivos;

           // if ($r->documento != "null") {
           //     $guardarArchivo = $this->guardarArchivo($r->documento, 'ArchivosLiquidaciones/');
           //     // el documento de solicitud de vacaciones se almacenara en ArchivosLiquidaciones/
           //     if ($guardarArchivo['estado'] == "success") {
           //         $crear->documento = $guardarArchivo['documento'];
           //     } else {
           //         return $guardarArchivo;
           //     }
           // }

           $crear->activo = 'S';
           if ($crear->save()) {
            $ultimo_dia_basico = VacDiasBasicosDevengados::where('vac_historial_id',$r->vacaciones_historial_id)->latest()->first();
            $ultimo_dia_basico->dias_progresivos = $r->dias_legales;
            if($ultimo_dia_basico->save()){
                DB::commit();
                return [
                    'estado'=>true,
                    'mensaje'=>'Solicitud ingresada con exito'
                ];
            }
            DB::rollBack();
            return [
                'estado'=>false,
                'mensaje'=>'No se ha registrado la solicitud'
            ];
           }
           DB::rollBack();
           return [
               'estado'=>false,
               'mensaje'=>'No se ha registrado la solicitud'
           ];
       }else{
           return $verify;
       }
    }

    protected function validar_dias_basicos_mayor_a_dias_legales($r){

        $dias_b = DB::select("SELECT sum(coalesce(dias_basicos,0)) dias_basicos,
            sum(coalesce(dias_progresivos, 0)) dias_progresivos
                             from vac_dias_basicos_devengados where vac_historial_id = ?",
                        [$r->vacaciones_historial_id]);

       $dias_b_disponibles =  $dias_b[0]->dias_basicos - $dias_b[0]->dias_progresivos;
       if($dias_b_disponibles <  $r->dias_legales ){
           return ["estado"=>false,"mensaje"=>"Los días que solicita no estan en el rango de sus dias básicos devengados"];
       }
       return ["estado"=>true];

    }

    protected function listar_vac_solicitud_por_vac_historial($id){
        // $list = $this->where('vac_historial_id',$id)->get();
        $list = DB::select("SELECT
                                id,
                                vac_historial_id,
                                TO_CHAR(fecha_solicitud,'dd/mm/yyyy') fecha_solicitud,
                                TO_CHAR(fecha_inicio, 'dd/mm/yyyy') fecha_inicio,
                                TO_CHAR(fecha_termino, 'dd/mm/yyyy') fecha_termino,
                                dias_legales,
                                dias_progresivos,
                                documento
                            FROM vac_solicitud where vac_historial_id = ?", [$id]);
        if(count($list) > 0){
            return [
                'estado'=>true,
                'solicitudes' => $list
            ];
        }
    }

    //proceso para guadar el archivo
    protected function guardarArchivo($archivo, $ruta)
    {
        $filenameext = $archivo->getClientOriginalName();
        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = $filename . '_' . time() . '.' . $extension;
        $rutaDB = 'storage/' . $ruta . $nombreArchivo;
        $guardar = Storage::put($ruta . $nombreArchivo, (string) file_get_contents($archivo), 'public');

        if ($guardar) {
            return ['estado' =>  'success', 'documento' => $rutaDB];
        } else {
            return ['estado' =>  'failed', 'mensaje' => 'Error al intentar guardar el archivo.'];
        }
    }
}
