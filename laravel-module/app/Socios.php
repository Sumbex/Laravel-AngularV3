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
                            s.id,
                            s.rut,
                            s.nombres,
                            s.a_paterno,
                            s.a_materno,
                            case when u.rol = 10 then 'Portal Socio Asignado'
                                when u.rol = 5 then 'Portal Administrativo y Socio Asignados'
                                when u.rol = 1 then 'Portal Administrativo Asignado'
                                else 'Nigun permiso asignado'
                            end as permiso

                            from socios s
                            left join users u on u.rut = s.rut
						where lower(CONCAT(s.nombres,' ',s.a_paterno,' ',s.a_materno)) like  '%$busca%'");

    	return $filtro;
	}
	
	protected function nombre_por_rut($rut, $socio)
	{
		$cl =  DB::table('cargas_legales_socio')
		->select(['nombres','apellido_paterno','apellido_materno'])
		->where(['socio_id'=>$socio])
		->whereRaw("LOWER(regexp_replace(rut, '[^\w]+','','g')) = LOWER('".$rut."')")
		->first();

		$ben = DB::table('socio_beneficiario')
		->select(['nombres','apellido_paterno','apellido_materno'])
		->where(['socio_id'=>$socio])
		->whereRaw("LOWER(regexp_replace(rut, '[^\w]+','','g')) = LOWER('".$rut."')")
		->first();

		$con =  DB::table('socio_conyuge')
		->select(['nombres','apellido_paterno','apellido_materno'])
		->where(['socio_id'=>$socio])
		->whereRaw("LOWER(regexp_replace(rut, '[^\w]+','','g')) = LOWER('".$rut."')")
		->first();

		$ps =  DB::table('padres_suegros_socio')
		->select(['nombres','apellido_paterno','apellido_materno'])
		->where(['socio_id'=>$socio])
		->whereRaw("LOWER(regexp_replace(rut, '[^\w]+','','g')) = LOWER('".$rut."')")
		->first();


		if ($cl) { return $cl->nombres.' '.$cl->apellido_paterno.' '.$cl->apellido_materno;  }
		if ($ben) { return $ben->nombres.' '.$ben->apellido_paterno.' '.$ben->apellido_materno;  }
		if ($con) { return $con->nombres.' '.$con->apellido_paterno.' '.$con->apellido_materno; }
		if ($ps) {  return $ps->nombres.' '.$ps->apellido_paterno.' '.$ps->apellido_materno; }
		return 'Sin nombre..';

                                   

	}
}
