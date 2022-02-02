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
        $crear = $this;
        $crear->vac_historial_id = $r->vacaciones_historial_id;
        $crear->fecha_solicitud = $r->fecha_solicitud;
        $crear->fecha_inicio = $r->fecha_inicio;
        $crear->fecha_termino = $r->fecha_termino;
        $crear->dias_legales = $r->dias_legales;
        $crear->dias_progresivos = $r->dias_progresivos;

        if ($r->documento != "null") {
            $guardarArchivo = $this->guardarArchivo($r->documento, 'ArchivosLiquidaciones/');
            // el documento de solicitud de vacaciones se almacenara en ArchivosLiquidaciones/
            if ($guardarArchivo['estado'] == "success") {
                $crear->documento = $guardarArchivo['documento'];
            } else {
                return $guardarArchivo;
            }
        }

        $crear->activo = 'S';
        if ($crear->save()) {
            return [
                'estado'=>true,
                'mensaje'=>'Solicitud ingresada con exito'
            ];
        }
        return [
            'estado'=>false,
            'mensaje'=>'No se ha registrado la solicitud'
        ];
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
