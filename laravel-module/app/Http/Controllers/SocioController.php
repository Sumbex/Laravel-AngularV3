<?php

namespace App\Http\Controllers;

use App\Socios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocioController extends Controller
{
    public function ingresar(Request $r)
    {
    	$s = new Socios;


    	return $this->subir_imagen($r->file('foto'), 'storage/socios_fotos');
    	

    	// $s->nombres = $r->nombres;
    	// $s->a_paterno = $r->a_paterno;
    	// $s->a_materno = $r->a_materno;
    	// $s->rut = $r->rut;
    	// $s->fecha_nacimiento = $r->fecha_nacimiento;

    	// if ($s->save()) {
    	// 	return ['estado'=>'success','mensaje'=>'Socio ingresado'];
    	// }
    }


    public function subir_imagen($get_img,$url)
    {
    	$img=$get_img;
    	$nombre=$img->getClientOriginalName();	
    	if($img->move($url, $nombre)){
    		return true;
    	}
    	return false;
    }
}
