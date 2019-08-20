<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PortalSocioCuentaSindical extends Model
{
    protected $table = "cuenta_sindicato";

    protected function div_fecha($value)
    {
        $fecha = $value;
        $ano = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $ano, 'mes' => $mes, 'dia' => $dia
        ];
    }
    protected function anio_tipo_id($value)
    {
        return DB::table('anio')->where('descripcion', $value)->first();
    }

    protected function mes_tipo_id($value)
    {
        return DB::table('mes')->where('id', $value)->first();
    }

    protected function traerCuentaSindical($anio, $mes)
    {
        $CS = DB::table('cuenta_sindicato as cs')
            ->select([
                'cs.id',
                DB::raw("concat(cs.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'cs.numero_documento',
                'cs.archivo_documento',
                'cs.descripcion',
                'cs.monto_ingreso',
                'cs.monto_egreso',
                'cs.tipo_cuenta_sindicato',
                'cs.definicion'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->where([
                'cs.activo' => 'S',
                'cs.anio_id' => $anio,
                'cs.mes_id' => $mes,
                'cs.detalle_camping' => null
            ])
            ->get();

        if (!$CS->isEmpty()) {
            return ['estado' => 'success', 'CS' => $CS];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }
}
