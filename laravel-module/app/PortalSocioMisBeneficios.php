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
                'p.tipo_prestamo as tipo',
                /* 'tp.descripcion as tipo', */
                'p.estado_prestamo',
                'p.estado_abono'
            ])
            ->join('anio as a', 'a.id', 'p.anio_id')
            ->join('mes as m', 'm.id', 'p.mes_id')
            /* ->join('tipo_prestamo as tp', 'tp.id', 'p.tipo_prestamo') */
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

    protected function traerPagosPrestamos($id, $tipo)
    {
        switch ($tipo) {
            case 1:
                $salud = DB::table('p_salud_retornable as psr')
                    ->select([
                        'psr.id',
                        DB::raw("concat(psr.dia,' de ',m.descripcion,', ',a.descripcion) as fecha_pago"),
                        'psr.prestamo_id',
                        'psr.monto_restante',
                        DB::raw("concat(psr.numero_cuota,'/',psr.cuotas) as cuota"),
                        'psr.monto_dia_sueldo as monto_sueldo',
                        'psr.monto_trimestral',
                        'psr.monto_termino_conflicto as monto_conflicto',
                        'psr.estado_prestamo',
                        'psr.estado_abono',
                    ])
                    ->join('anio as a', 'a.id', 'psr.anio_id')
                    ->join('mes as m', 'm.id', 'psr.mes_id')
                    ->join('cs_prestamo as p', 'p.id', 'psr.prestamo_id')
                    ->where([
                        'psr.estado_prestamo' => 'pagando',
                        'psr.prestamo_id' => $id,
                        'p.activo' => 'S',
                        'p.socio_id' => $this->socioID()
                    ])
                    ->get();

                if (!$salud->isEmpty()) {
                    return ['estado' => 'success', 'pagos' => $salud];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Aun no tienes pagos registrados en este prestamo.'];
                }
                break;

            case 2:
                # code...
                break;

            case 3:
                # code...
                break;

            default:
                # code...
                break;
        }
    }
}
