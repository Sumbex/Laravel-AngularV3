<?php

namespace App\Http\Controllers;

use App\VacDiasProgresivosDevengados;
use Illuminate\Http\Request;

class VacDiasProgresivosDevengadosController extends Controller
{
    public function dias_progresivos_devengados_disponibles($vac_historial_id){
        return VacDiasProgresivosDevengados::dias_progresivos_devengados_disponibles($vac_historial_id);
    }

    public function historial_dias_progresivos_devengados($vac_historial_id){
        return VacDiasProgresivosDevengados::historial_dias_progresivos_devengados($vac_historial_id);
    }

}
