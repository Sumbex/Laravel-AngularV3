<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class CajaChica extends Model
{
    protected $table = "cs_caja_chica";

    protected function ingresarCajaChica($request){
        $caja = new CajaChica;

        $caja->anio_id = $request->anio_id;
        $caja->mes_id = $request->mes_id;
        $caja->dia = $request->dia;
        $caja->numero_documento = $request->numero_documento;
        $caja->archivo_documento = '/doc/archivo.pdf';
        $caja->descripcion = $request->descripcion;
        $caja->monto_egreso =  $request->monto_egreso;
        $caja->definicion = 3;
        $caja->user_crea = Auth::user()->id;
        $caja->activo = "S";

        if ($caja->save()) {
    		return ['status'=>'success', 'mensaje'=>'Insertado'];
    	}
        
    }

    protected function existeCajaChica($anio, $mes){
        //cuenta_sindicato
        $cSindicato = DB::table('cuenta_sindicato')
        ->select([
            'tipo_cuenta_sindicato',
            'monto_egreso',
            'anio_id',
            'mes_id'
        ])
        ->where([
            'cuenta_sindicato.activo'=>'S',
            'cuenta_sindicato.anio_id' => $anio, 
            'cuenta_sindicato.mes_id' => $mes,
            'cuenta_sindicato.tipo_cuenta_sindicato' => 3
        ])
        ->get();
       
        if(!$cSindicato->isEmpty()){
            return $cSindicato[0]->monto_egreso;
        }else{
            return "aun no a realizado el ingreso a caja chica";
        }

    }

    protected function traerCajaChica($anio, $mes){
      
        $caja = DB::table('cs_caja_chica as cc')
        ->select([
            'cc.id',
    		DB::raw("concat(cc.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
    		'cc.numero_documento',
    		'cc.archivo_documento',
    		'cc.descripcion',
    		'cc.monto_egreso'
        ])
        ->join('anio as a','a.id','anio_id')
    	->join('mes as m','m.id','mes_id')
		->where([
				'cc.activo'=>'S',
				'cc.anio_id' => $anio, 
				'cc.mes_id' => $mes,
        ])
        ->get();

        return $caja;
    }
    
    
}
