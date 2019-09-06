<?php

namespace App;

use App\CbeNacimiento;
use App\CbeFallecimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Cuentabienestar extends Model
{
    protected $table = "cuenta_bienestar";


    protected function insertar_cuenta_sindical_gas($r)//aqui cae la del gas
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
     protected function insertar_cuenta_sindical($r)//caja chica, reunion y votacion
    {
        $f = $this->div_fecha($r->fecha);
        $anio = $this->anio_tipo_id($f['anio']);

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
                return [ 'estado'=>'success', 'mensaje'=> 'Item ingresado correctamente' ];
            }
            return ['estado'=>'failed', 'mensaje'=>'Error al guardar item'];
        
    }

    protected function insertar_fall_nac_gm($r)
    {
         $f = $this->div_fecha($r->fecha);
        $anio = $this->anio_tipo_id($f['anio']);

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

                switch ((string)$cbe->tipo_cuenta_bienestar_id) {
                    case '4'://Fallecimiento
                        return CbeFallecimiento::insertar($cbe->id, $r->socio_id);
                    break;
                     case '5'://Nacimiento
                        return CbeNacimiento::insertar($cbe->id, $r->socio_id);
                    break;
                     case '7'://Gastos medicos
                        return CbeGastosMedicos::insertar($cbe->id, $r->socio_id);
                    break;
                    
                    default:
                        # code...
                    break;
                }

            }
            return ['estado'=>'failed', 'mensaje'=>'Error al guardar item'];
    }

    protected function listar($anio, $mes)
    {
        $list = DB::select("SELECT 
                        cbe.id,
                        concat(cbe.dia,' de ',m.descripcion,',',a.descripcion) as fecha,
                        numero_documento_1,
                        archivo_documento_1,
                        monto_ingreso,
                        monto_egreso,
                        definicion,
                        tcb.id as tipo_cuenta_bienestar_id,
                        tcb.descripcion as tipo_cuenta,
                        cbe.descripcion 
                    from cuenta_bienestar cbe
                    inner join anio as a on a.id = cbe.anio_id
                    inner join mes as m on m.id = cbe.mes_id
                    inner join tipo_cuenta_bienestar as tcb on tcb.id = cbe.tipo_cuenta_bienestar_id
                    where cbe.mes_id = $mes and cbe.anio_id= $anio and cbe.activo ='S'
                    --order by tcb.id ASC 
                    order by tcb.orden ASC, cbe.dia asc "
        );
        
        if ($list > 0 ) {
            return $list;
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

     protected function resultado_cuenta_sindical($anio, $mes)
	{
			$ingreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			// ->where('cuenta_bienestar.detalle_camping', null)
			->sum('monto_ingreso');
			$egreso_total = $this::where(['activo'=>'S', 'anio_id' => $anio, 'mes_id' => $mes ])
			// ->where('cuenta_bienestar.detalle_camping', null)
			->sum('monto_egreso');
			$total = $ingreso_total - $egreso_total;

			$anterior = DB::table('cbe_cierre_mensual')->select('inicio_mensual')->where([
				'mes_id'=> $mes, 'anio_id' => $anio
			])->first();

			return [
				'ingreso_total' => $ingreso_total,
				'egreso_total' => $egreso_total,
				'total_actual' => $total,
				'total_resumen' => $total.' + '. $anterior->inicio_mensual.' = '.($total + $anterior->inicio_mensual),
				'total_final' => ($total + $anterior->inicio_mensual)
			];

	}

}
