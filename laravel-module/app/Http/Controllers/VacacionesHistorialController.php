<?php

namespace App\Http\Controllers;

use App\VacacionesSolicitud;
use App\VacacionHistorial;
use App\VacDiasBasicosDevengados;
use Illuminate\Http\Request;

class VacacionesHistorialController extends Controller
{
    public function crear(Request $r){

        return VacacionHistorial::crear($r);
    }

    public function listar(){
        return VacacionHistorial::listar();
    }

    public function listar_por_vacacion_historial($id){
        return VacacionHistorial::listar_por_vac_historial($id);
    }

    public function crear_solicitud_vacaciones(Request $r){

        return VacacionesSolicitud::crear($r);
    }

    public function edita_solicitud_vacaciones(Request $r){

        return VacacionesSolicitud::editar($r);
    }


    public function listar_solicitud_vacaciones($id){
        return VacacionesSolicitud::listar_vac_solicitud_por_vac_historial($id);
    }

    public function detectar_incremento_mensaul_diasbasicos($id_vac_historial){
        return VacDiasBasicosDevengados::detectar_incremento_de_dias_basicos($id_vac_historial);
    }

    public function historial_mensaul_dias_basicos_devengados($vac_historial_id){
        return VacDiasBasicosDevengados::historial_mensaul_dias_basicos_devengados($vac_historial_id);
    }

    public function subir_archivo(Request $r){
        return VacacionesSolicitud::subir_archivo($r);
    }


}
