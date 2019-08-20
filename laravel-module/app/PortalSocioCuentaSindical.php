<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortalSocioCuentaSindical extends Model
{
    protected $table = "cuenta_sindicato";

    protected function traerCuentaSindical()
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
                'cs.definicion',
                'cs.interes',
                'cs.p_e',
                'cs.detalle_camping'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            /* ->where([
                'activo' => 'S',
                'socio_id' => $this->socioLogeado()->id
            ]) */
            ->get();

        if (!$CS->isEmpty()) {
            return ['estado' => 'success', 'CS' => $CS];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes datos ingresados.'];
        }
    }
}
