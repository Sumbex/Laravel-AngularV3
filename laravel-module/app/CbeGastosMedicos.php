<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CbeGastosMedicos extends Model
{
    protected $table = 'cbe_gastos_medicos';

    protected function insertar($cbe_id, $socio_id)
    {
        $this->socio_id = $socio_id;
        $this->cuenta_bienestar_id = $cbe_id;
        $this->activo = 'S';
        if ($this->save()) {
            return [ 'estado'=>'success', 'mensaje'=> 'Item ingresado correctamente' ];
        }
        return [ 'estado'=>'failed', 'mensaje'=> 'Error al guardar item' ];
    }

    protected function mis_beneficios($socio_id)
    {
        $listar = DB::select("SELECT 
                                concat(cbe.dia,' de ',m.descripcion,',',a.descripcion) as fecha,
                                s.rut,
                                concat(s.nombres,' ',s.a_paterno,' ',s.a_materno) nombre,
                                cbe.descripcion,
                                cbe.monto_egreso
                            from cbe_gastos_medicos gm
                            inner join cuenta_bienestar cbe on cbe.id = gm.cuenta_bienestar_id
                            inner join socios s on s.id = gm.socio_id
                            inner join anio a on a.id = cbe.anio_id
                            inner join mes m on m.id = cbe.mes_id
                            where gm.socio_id = $socio_id  and cbe.activo = 'S'");

         if (count($listar) > 0) {
    		return $listar;
    	}else{
    		return '';
    	}
    }
}
