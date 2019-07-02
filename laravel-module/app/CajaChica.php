<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CuentaSindicatoController;

class CajaChica extends Model
{
    protected $table = "cs_caja_chica";

    protected function div_fecha($value)//funciona con input type date 
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

    protected function ingresarCajaChica($request){
        $caja = new CajaChica;

        $fecha = $this->div_fecha($request->fecha);

        $anio = $this->anio_tipo_id($fecha['anio']);

        $caja->anio_id = $anio->id;
        $caja->mes_id = $fecha['mes'];
        $caja->dia = $fecha['dia'];
        $caja->numero_documento = $request->numero_documento;
        $caja->archivo_documento = '/doc/archivo.pdf';
        $caja->descripcion = $request->descripcion;

        switch ($request->definicion) {
            case '1':  
                $caja->monto_ingreso = $request->monto; 
              //  $cs->saldo_actual = $s_a + $r->monto;
            break;
            case '2':  
                $caja->monto_egreso = $request->monto; 
               // $cs->saldo_actual = $s_a - $r->monto;
            break;
        }

        $caja->definicion = $request->definicion;
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
