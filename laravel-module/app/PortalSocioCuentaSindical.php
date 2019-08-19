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
                'numero_documento',
                'archivo_documento',
                'descripcion',
                'monto_ingreso',
                'monto_egreso',
                'tipo_cuenta_sindicato',
                'definicion',
                'interes',
                'p_e',
                'detalle_camping'
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
