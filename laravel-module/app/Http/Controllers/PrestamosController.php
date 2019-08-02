<?php

namespace App\Http\Controllers;

use App\Cs_prestamos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrestamosController extends Controller
{

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
		return Cs_prestamos::prestamosFinales($anio, $mes);
	}

	public function PagoPrestamos(Request $request)
	{
		return Cs_prestamos::pagoPrestamos($request);
	}

	public function TraerTipoAbonos()
	{
		return Cs_prestamos::traerTipoAbonos();
	}

	public function PagoAbonos(Request $request)
	{
		return Cs_prestamos::pagoAbonos($request);
	}
}
