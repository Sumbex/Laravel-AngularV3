<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Cuentabienestar extends Model
{
    protected $table = "cuenta_bienestar";


    protected function insertar_cuenta_gas($r)
    {
        $f = $this->div_fecha($r->fecha);
        $anio = $this->anio_tipo_id($f['anio']);

       

        $verify = $this->where([
            'mes_id' => $f['mes'],
            'anio_id'=> $anio->id,
            'activo'=>'S',
            'tipo_cuenta_bienestar_id' => '1'
        ])->first();

        if (!empty($verify)) {
            return ['estado'=>'failed', 'mensaje'=>'Ya existe la cuenta del gas para este mes'];
        }
        else{

            $cbe = $this;
            $cbe->anio_id = $anio->id;
            $cbe->mes_id = $f['mes'];
            $cbe->dia = $f['dia'];
            $cbe->tipo_cuenta_bienestar_id = $r->tipo_cuenta_bienestar_id;
            $cbe->numero_documento_1 = $r->numero_documento_1;
            $cbe->archivo_documento_1 = 'documento.pdf' /*$r->archivo_documento_1*/;
            $cbe->definicion = $r->definicion;
            $cbe->activo = 'S';

            if ($r->definicion == '1') { $cbe->monto_ingreso = $r->monto; }
            if ($r->definicion == '2') { $cbe->monto_egreso = $r->monto; }

            $cbe->descripcion = $r->descripcion;

            if ($cbe->save()) {
                return [ 'estado'=>'success', 'mensaje'=> 'Cuenta del gas ingresada correctamente' ];
            }
            return ['estado'=>'failed', 'mensaje'=>'Error al guardar item de cuenta del gas'];
        }
    }



    public function div_fecha($value)//funciona con input type date 
    {
    	$fecha = $value;
		$ano = substr($fecha, -10, 4);
		$mes = substr($fecha, -5, 2);
		$dia = substr($fecha, -2, 2);
		return [
			'anio' => $ano, 
			'mes'  => $mes, 
			'dia'  => $dia
		];
    }
    public function anio_tipo_id($value)
    {

    	$data = DB::table('anio')->where('descripcion', $value)->first();

    	if(!empty($data)){

    	 	return $data;
    	} 
    		
    }

}
