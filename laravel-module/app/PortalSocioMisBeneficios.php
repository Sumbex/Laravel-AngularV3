<?php

namespace App;

use App\PortalSocio;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PortalSocioMisBeneficios extends Model
{
    protected function socioID()
    {
        return PortalSocio::socioLogeado()->id;
    }

    protected function traerPrestamos()
    {
        $prestamos = DB::table('cs_prestamo as p')
            ->select([
                'p.id',
                DB::raw("concat(p.dia,' de ',m.descripcion,', ',a.descripcion) as fecha"),
                'p.descripcion_prestamo as descripcion',
                'p.transferencia_bancaria as codigo',
                'p.archivo',
                'p.egreso',
                'p.cuotas',
                'tp.descripcion as tipo',
                'p.estado_prestamo',
                'p.estado_abono'
            ])
            ->join('anio as a', 'a.id', 'p.anio_id')
            ->join('mes as m', 'm.id', 'p.mes_id')
            ->join('tipo_prestamo as tp', 'tp.id', 'p.tipo_prestamo')
            ->where([
                'p.activo' => 'S',
                'p.socio_id' => $this->socioID()
            ])
            ->get();

        if (!$prestamos->isEmpty()) {
            return ['estado' => 'success', 'prestamos' => $prestamos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no tienes prestamos pedidos.'];
        }
    }
}
