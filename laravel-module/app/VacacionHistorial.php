<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VacacionHistorial extends Model
{
    protected $table = "vac_historial";

    protected function crear($r)
    {


        $vh = $this;
        $vh->liq_empleado_id = $r->empleado;
        $vh->d_basicos_devengados = $r->d_basicos_devengadp;
        $vh->d_progresivos_devengados = $r->d_progresivos_dvengados;
        $vh->activo = 'S';
        if ($vh->save()) {
            return [
                'estado' => true,
                'mensaje' => 'Registro exitoso',
                'listado' => $this->all()
            ];
        }
        return [
            'estado' => false,
            'mensaje' => 'No se ha podido hacer el registro'
        ];
    }

    protected function listar()
    {

        $list = DB::select("SELECT
                               vh.id,
                               vh.d_basicos_devengados,
                               vh.d_progresivos_devengados,
                               em.rut_trabajador,
                               em.nombre_trabajador,
                               em.cargo,
                               em.nombre_afp,
                               em.nombre_isapre,
                               TO_CHAR(em.created_at,'dd/mm/yyyy') created_at
                               FROM vac_historial AS vh
                               INNER JOIN liq_empleado AS em ON em.id = vh.liq_empleado_id

        ");

        if (count($list) > 0) {
            return [
                'estado' => true,
                'lista' => $list
            ];
        }
        return [
            'estado' => false,
            'lista' => []
        ];
    }
    protected function listar_por_vac_historial($id)
    {

        $list = DB::select("SELECT
                                vh.id,
                                vh.d_basicos_devengados,
                                vh.d_progresivos_devengados,
                                em.rut_trabajador,
                                em.nombre_trabajador,
                                em.cargo,
                                em.nombre_afp,
                                em.nombre_isapre,
                                TO_CHAR(em.created_at,'dd/mm/yyyy') created_at
                            FROM vac_historial AS vh
                            INNER JOIN liq_empleado AS em ON em.id = vh.liq_empleado_id
                            WHERE vh.id = $id
        ");

        if (count($list) > 0) {
            return [
                'estado' => true,
                'lista' => $list
            ];
        }
        return [
            'estado' => false,
            'lista' => []
        ];
    }
}
