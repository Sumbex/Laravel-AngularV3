<?php

namespace App\Http\Controllers;

use App\VacacionesSolicitud;
use App\VacacionHistorial;
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

    public function listar_solicitud_vacaciones($id){
        return VacacionesSolicitud::listar_vac_solicitud_por_vac_historial($id);
    }
}
