<?php

namespace App\Http\Controllers;

use App\Cs_prestamos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrestamosController extends Controller
{
	public function insertar_cs(Request $r)
	{
		$f = $this->div_fecha($r->fecha);

		$anio = $this->anio_tipo_id($f['anio']);
		//return response()->json($r);

		$cs_prestamo = new Cs_prestamos;

		$cs_prestamo->anio_id = $anio->id/*$f['anio']*/;
		$cs_prestamo->mes_id = $f['mes'];
		$cs_prestamo->dia = $f['dia'];
		$cs_prestamo->numero_documento = $r->n_documento;
		$cs_prestamo->archivo_documento = '/doc/archivo.pdf'; //valor por mientras
		$cs_prestamo->tipo_cuenta_sindicato = $r->tipo_cuenta_sindicato;
		$cs_prestamo->descripcion = $r->descripcion;
		switch ($r->definicion) {
			case '1':
				$cs_prestamo->monto_ingreso = $r->monto;
				//$cs_prestamo->saldo_actual = $s_a + $r->monto;
				break;
			case '2':
				$cs_prestamo->monto_egreso = $r->monto;
				//$cs_prestamo->saldo_actual = $s_a - $r->monto;
				break;
		}
		$cs_prestamo->activo = 'S';
		$cs_prestamo->definicion = $r->definicion;
		$cs_prestamo->user_crea = Auth::user()->id;
		if ($cs_prestamo->save()) {
			return "success";
		}
		return "failed";
	}

	public function listar_cs_prestamo($anio, $mes)
	{
		$cm_txt = 'c_s_cierre_mensual';
		$c_m = DB::table($cm_txt)->where([
			'activo' => 'S', 'anio_id' => $anio, 'mes_id' => $mes,
		])->first();


		if (!empty($c_m->inicio_mensual)) {
			$s_a = $c_m->inicio_mensual;
			$listar = Cs_prestamos::where(['activo' => 'S', 'anio_id' => $anio, 'mes_id' => $mes])->get();

			$tomar = true;


			for ($i = 0; $i < count($listar); $i++) {

				switch ($listar[$i]->definicion) {
					case '1':
						if ($tomar == true) {
							$listar[$i]->saldo_actual_raw = $s_a + $listar[$i]->monto_ingreso;
							$tomar = false;
						} else {
							$listar[$i]->saldo_actual_raw = $listar[$i - 1]->saldo_actual_raw  + $listar[$i]->monto_ingreso;
						}

						break;
					case '2':
						if ($tomar == true) {
							$listar[$i]->saldo_actual_raw = $s_a - $listar[$i]->monto_egreso;
							$tomar = false;
						} else {

							$listar[$i]->saldo_actual_raw = $listar[$i - 1]->saldo_actual_raw - $listar[$i]->monto_egreso;
						}
						break;
				}
			}
			return [
				'lista' => $listar,
				'totales' => $this->calculo_cs_prestamo($anio, $mes)
			];
		}
	}



	public function calculo_cs_prestamo($anio, $mes)
	{
		$ingreso_total = Cs_prestamos::where(['activo' => 'S', 'anio_id' => $anio, 'mes_id' => $mes])
			->sum('monto_ingreso');
		$egreso_total = Cs_prestamos::where(['activo' => 'S', 'anio_id' => $anio, 'mes_id' => $mes])
			->sum('monto_egreso');
		$total = $ingreso_total - $egreso_total;

		return [
			'ingreso_total' => $ingreso_total,
			'egreso_total' => $egreso_total,
			'total' => $total
		];
	}









	public function div_fecha($value) //funciona con input type date 
	{
		$fecha = $value;
		$ano = substr($fecha, -10, 4);
		$mes = substr($fecha, -5, 2);
		$dia = substr($fecha, -2, 2);
		return [
			'anio' => $ano, 'mes' => $mes, 'dia' => $dia
		];
	}
	public function anio_tipo_id($value)
	{
		return DB::table('anio')->where('descripcion', $value)->first();
	}

	//------------------------------------------------->BRYANM

	public function IngresarPrestamo(Request $request)
	{
		return Cs_prestamos::ingresarPrestamo($request);
	}

	public function TraerTipoPrestamos()
	{
		return Cs_prestamos::traerTipoPrestamos();
	}

	public function TraerSocio($rut)
	{
		return Cs_prestamos::traerSocio($rut);
	}

	public function TraerPrestamos($anio, $mes)
	{
		return Cs_prestamos::prestamosTotales($anio, $mes);
	}

	public function GuardarMontoCierrePrestamo(Request $request)
	{
		return Cs_prestamos::guardarMontoCierrePrestamo($request);
	}

	public function TraerMontoCierrePrestamo($anio, $mes)
	{
		return Cs_prestamos::traerMontoCierrePrestamo($anio, $mes);
	}
}
