<?php

namespace App;

use App\CajaChicaBienestar;
use Illuminate\Database\Eloquent\Model;

class PortalSocioCuentaBienestar extends Model
{
    protected function existeInicioMensual($anio, $mes)
    {
        return CajaChicaBienestar::existeInicioMensual($anio, $mes);
    }

    protected function calcularValorCajaChicaMesAnterior($anio, $mes)
    {
        return CajaChicaBienestar::calcularValorCajaChicaMesAnterior($anio, $mes);
    }

    protected function existeCajaChica($anio, $mes)
    {
        return CajaChicaBienestar::existeCajaChica($anio, $mes);
    }

    protected function traerCajaChicaTotal($anio, $mes)
    {
        return CajaChicaBienestar::traerCajaChicaTotal($anio, $mes);
    }

    protected function traerCajaChica($anio, $mes, $MC, $total)
    {
        return CajaChicaBienestar::traerCajaChica($anio, $mes, $MC, $total);
    }

    protected function totalesCajaChica($anio, $mes)
    {
        return CajaChicaBienestar::totalesCajaChica($anio, $mes);
    }
}
