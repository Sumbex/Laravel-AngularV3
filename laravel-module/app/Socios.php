<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Socios extends Model
{

    protected function filtrar($search)
    {
    	$busca = mb_strtolower($search);

    	$filtro = DB::select("SELECT 
						to_char(fecha_nacimiento, 'dd-mm-yyyy') as fecha_nacimiento_view,
						to_char(fecha_ingreso, 'dd-mm-yyyy') as fecha_ingreso_view,
						to_char(fecha_egreso, 'dd-mm-yyyy') as fecha_egreso_view,
						fecha_nacimiento,
						fecha_ingreso,
						fecha_egreso,
						id,
						rut,
						nombres,
						a_paterno,
						a_materno

    					from socios
						where lower(CONCAT(nombres,' ',a_paterno,' ',a_materno)) like  '%$busca%'");

    	return $filtro;
    }
}
