<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cs_prestamos extends Model
{
    protected $table = 'cs_prestamos';

    /* protected function ingresarPrestamo($request)
    {
        $prestamo = new Cs_prestamos;
    } */

    protected function traerTipoPrestamos()
    {
        $tipo = DB::table('tipo_prestamo')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();

        return $tipo;
    }

    protected function traerTipoPago()
    {
        $tipo = DB::table('tipo_pago')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();

        return $tipo;
    }

    protected function traerTipos()
    {
        $tPre = $this->traerTipoPrestamos();
        $tPa = $this->traerTipoPago();

        return ['tipo_prestamo' => $tPre, 'tipo_pago' => $tPa];
    }

    protected function traerTipoAbono()
    {
        $tipoA = DB::table('tipo_abono_cuotas')
            ->select([
                'id',
                'tipo_pago_id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();
        return $tipoA;
    }

    protected function traerSocio($rut)
    {
        $socio = DB::table('socios')
            ->select([
                'id',
                'rut',
                DB::raw("concat(nombres,' ',a_paterno,' ',a_materno) as socio"),
                'foto'
            ])
            ->where([
                'rut' => $rut,
                'activo' => 'S',
            ])
            ->get();

        return $socio;
    }
}
