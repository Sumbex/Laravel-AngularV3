<?php

namespace App\Http\Controllers;

use App\Socios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocioController extends Controller
{
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

    public function ingresar(Request $r)
    {

        //dd($r->all());
    	// dd($r->file('foto'));
    	$s = new Socios;
    	// $valida_foto = $this->subir_imagen($r->file('foto'), 'storage/socios_fotos'); 

    	// if($valida_foto['estado'] == "true"){

    	// 	$s->foto = "storage/socios_fotos/".$valida_foto['nombre'];
    	// }
    	

    	$s->nombres = $r->nombres;
    	$s->a_paterno = $r->a_paterno;
    	$s->a_materno = $r->a_materno;
    	$s->rut = $r->rut;
    	$s->fecha_nacimiento = $r->fecha_nacimiento;
    	$s->activo = 'S';

    	if ($s->save()) {
    		return ['estado'=>'success','mensaje'=>'Socio ingresado'];
    	}
    }


    public function subir_imagen($get_img,$url)
    {

    	$img=$get_img;
    	$nombre=$img->getClientOriginalName();	
    	
    	if($img->move($url, $nombre)){
    		return ['estado'=>"true",'nombre'=>$nombre];
    	}
    	return "false";
    }
}
