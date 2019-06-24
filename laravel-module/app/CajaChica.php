<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    protected $table = "c_s_caja_chica";

    protected function ingresarCajaChica($request){
        $caja = $this;

        $caja->id = 5423;
        $caja->anio_id = $request->anio_id;
        $caja->mes_id = $request->mes_id;
        $caja->dia = $request->dia;
        $caja->numero_documento = $request->numero_documento;
        $caja->archivo_documento = $request->archivo_documento;
        $caja->descripcion = $request->descripcion;
        $caja->monto_ingreso = 123;
        $caja->monto_egreso =  321;
        $caja->definicion = $request->definicion;
        $caja->user_crea = $request->user_crea;
        $caja->activo = "S";

        dd($caja->save());
        if ($caja->save()) {
    		return ['status'=>'success', 'mensaje'=>'Insertado'];
    	}
        
        
    }
    
}
