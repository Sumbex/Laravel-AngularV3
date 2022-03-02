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
        $verify_prog = $this->dias_progresivos_devengados_disponibles($r->vacaciones_historial_id);
        $valores = $verify_prog["valores"][0];
        // return Response()->json((int)$valores->dias_progresivos_devengados);
        $total_prog = (int)$valores->dias_progresivos_devengados - (int)$valores->dias_progresivos_solicitud;
        if ($r->dias_progresivos > $total_prog) {
            return [
                "estado" => false,
                "mensaje" => "Los días que solicita no estan en el rango de sus dias progresivos devengados"
            ];
        }
        //    $ultimo_dia_basico = VacDiasBasicosDevengados::where('vac_historial_id',$r->vacaciones_historial_id)->latest()->first();
        //    return $ultimo_dia_basico;
        if ($verify['estado']) {



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
                //$ultimo_dia_basico = VacDiasBasicosDevengados::where('vac_historial_id',$r->vacaciones_historial_id)->latest()->first();
                //$ultimo_dia_basico->dias_progresivos = $r->dias_legales;
                $insert_dbd = VacDiasBasicosDevengados::registrar($crear, 'solicitud');
                $insert_vdpd = VacDiasProgresivosDevengados::registrar($crear, 'solicitud');
                if ($insert_dbd || $insert_vdpd) {
                    DB::commit();
                    return [
                        'estado' => true,
                        'mensaje' => 'Solicitud ingresada con exito'
                    ];
                }
                DB::rollBack();
                return [
                    'estado' => false,
                    'mensaje' => 'No se ha registrado la solicitud (1)'
                ];
            }
            DB::rollBack();
            return [
                'estado' => false,
                'mensaje' => 'No se ha registrado la solicitud (2)'
            ];
        } else {
            return $verify;
        }
    }

    protected function editar($r)
    {

        $verify = $this->validar_dias_basicos_mayor_a_dias_legales($r);
        $verify_prog = $this->dias_progresivos_devengados_disponibles($r->vacaciones_historial_id);
        $valores = $verify_prog["valores"][0];
        // return Response()->json((int)$valores->dias_progresivos_devengados);
        $total_prog = (int)$valores->dias_progresivos_devengados - (int)$valores->dias_progresivos_solicitud;
        // return "$r->dias_progresivos > $total_prog";
        if ($r->dias_progresivos > $total_prog) {
            return [
                "estado" => false,
                "mensaje" => "Los días que solicita no estan en el rango de sus dias progresivos devengados"
            ];
        }
        //    $ultimo_dia_basico = VacDiasBasicosDevengados::where('vac_historial_id',$r->vacaciones_historial_id)->latest()->first();
        //    return $ultimo_dia_basico;
        if ($verify['estado']) {
            DB::beginTransaction();
            $db = VacDiasBasicosDevengados::where("vac_solicitud_id", $r->id)->first();
            $ddb = VacDiasProgresivosDevengados::where("vac_solicitud_id", $r->id)->first();

            $update = $this->find($r->id);
            $update->fecha_solicitud = $r->fecha_solicitud;
            $update->fecha_inicio = $r->fecha_inicio;
            $update->fecha_termino = $r->fecha_termino;

            if($db){
                $update->dias_legales = $r->dias_legales;
            }
            if($ddb){
                $update->dias_progresivos = $r->dias_progresivos;
            }

            if ($update->save()) {
                $contar = 0;
                // update dias legales!!!
                if ($db) {
                    $contar++;
                    $db->dias_progresivos = $r->dias_legales;
                    $db->save();
                } else {
                    // evaluando ingreso si no existe item a actualizar
                }

                if ($ddb) {
                    $contar++;
                    $ddb->dias_progresivos_solicitud = $r->dias_progresivos;
                    $ddb->save();
                } else {
                    // evaluando ingreso si no existe item a actualizar
                }

                if ($contar > 0) {
                    DB::commit();
                    return [
                        "estado" => true,
                        "mensaje" => "Item actualizado correctamente"
                    ];
                }
                DB::commit();
                return [
                    "estado" => true,
                    "mensaje" => "Item actualizado pero sin cambios en los dias basicos o progresivos"
                ];
            }
            DB::rollBack();
            return [
                "estado" => true,
                "mensaje" => "No se ha actualizado el item"
            ];
        } else {
            return $verify;
        }
    }

    protected function subir_archivo($r)
    {

        $update = $this->find($r->id);
        if ($update) {
            if ($r->documento != "null") {
                $guardarArchivo = $this->guardarArchivo($r->archivo, 'ArchivosLiquidaciones/');
                // el documento de solicitud de vacaciones se almacenara en ArchivosLiquidaciones/
                if ($guardarArchivo['estado'] == "success") {
                    $update->documento = $guardarArchivo['documento'];
                    if ($update->save()) {
                        return [
                            'estado' => true,
                            'mensaje' => 'Documento ingresado correctamente'
                        ];
                    }
                    return [
                        'estado' => false,
                        'mensaje' => 'Documento no registrado'
                    ];
                } else {
                    return $guardarArchivo;
                }
            }
        } else {
            return [
                'estado' => false,
                'mensaje' => 'No register'
            ];
        }
    }
    protected function dias_progresivos_devengados_disponibles($vac_historial_id)
    {

        $query = DB::select(
            "SELECT
        sum(coalesce(dias_progresivos_devengados, 0)) dias_progresivos_devengados,
        sum(coalesce(dias_progresivos_solicitud, 0)) dias_progresivos_solicitud

    from vac_dias_progresivos_devengados where vac_historial_id = ?",
            [$vac_historial_id]
        );

        if (count($query) > 0) {
            return [
                'estado' => true,
                'valores' => $query
            ];
        }
        return [
            'estado' => false,
            'valores' => []
        ];
    }

    protected function validar_dias_basicos_mayor_a_dias_legales($r)
    {

        $dias_b = DB::select(
            "SELECT sum(coalesce(dias_basicos,0)) dias_basicos,
            sum(coalesce(dias_progresivos, 0)) dias_progresivos
                             from vac_dias_basicos_devengados where vac_historial_id = ?",
            [$r->vacaciones_historial_id]
        );

        $dias_b_disponibles =  $dias_b[0]->dias_basicos - $dias_b[0]->dias_progresivos;
        if ($dias_b_disponibles <  $r->dias_legales) {
            return ["estado" => false, "mensaje" => "Los días que solicita no estan en el rango de sus dias básicos devengados"];
        }
        return ["estado" => true];
    }

    protected function listar_vac_solicitud_por_vac_historial($id)
    {
        // $list = $this->where('vac_historial_id',$id)->get();
        $list = DB::select("SELECT
                                id,
                                vac_historial_id,
                                fecha_solicitud as fes,
                                TO_CHAR(fecha_solicitud,'dd/mm/yyyy') fecha_solicitud,
                                TO_CHAR(fecha_inicio, 'dd/mm/yyyy') fecha_inicio,
                                TO_CHAR(fecha_termino, 'dd/mm/yyyy') fecha_termino,
                                dias_legales,
                                dias_progresivos,
                                documento
                            FROM vac_solicitud where vac_historial_id = ? order by fes desc", [$id]);
        if (count($list) > 0) {
            return [
                'estado' => true,
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
