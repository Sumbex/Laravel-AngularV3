<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CbeFallecimiento extends Model
{
    protected $table = 'cbe_fallecimiento';

    protected function insertar($cbe_id, $socio_id, $rut)
    {
        $this->socio_id = $socio_id;
        $this->cuenta_bienestar_id = $cbe_id;
        $this->activo = 'S';
        $this->rut_fallecido = $rut;
        if ($this->save()) {
            return [ 'estado'=>'success', 'mensaje'=> 'Item ingresado correctamente' ];
        }
        return [ 'estado'=>'failed', 'mensaje'=> 'Error al guardar item' ];
    }

    protected function mis_beneficios($socio_id)
    {
        $listar = DB::select("SELECT 
                                concat(cbe.dia,' de ',m.descripcion,',',a.descripcion) as fecha,
                                f.rut_fallecido,
                               
                                cbe.descripcion,
                                cbe.monto_egreso
                                
                            from cbe_fallecimiento f
                            inner join cuenta_bienestar cbe on cbe.id = f.cuenta_bienestar_id
                            -- left join cargas_legales_socio c on c.rut = f.rut_fallecido
                            inner join anio a on a.id = cbe.anio_id
                            inner join mes m on m.id = cbe.mes_id
                            where f.socio_id = $socio_id  and cbe.activo = 'S'");

        if (count($listar) > 0) {
    		return $listar;
    	}else{
    		return '';
    	}
    }
}
