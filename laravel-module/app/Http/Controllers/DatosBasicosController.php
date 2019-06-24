<?php

namespace App\Http\Controllers;

use App\TipoCUentaSindicato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatosBasicosController extends Controller
{
    public function listar_anios()
    {
    	return DB::table('anio')->orderBy('descripcion','desc')->get();
    }
    public function anio_actual()	
    {
    	$anio = DB::select("select date_part('year',now()) as anio");

    	return $anio[0]->anio;
    }

    public function listar_meses()
    {
    	return DB::table('mes')->orderBy('id','asc')->get();
    }
    public function mes_actual()
    {
    	$mes_actual = DB::select("select date_part('month',now()) as mes");
    	$id = $mes_actual[0]->mes;
    	return $this->get_mes($id);
    }
    public function listar_definicion()
    {
    	return DB::table('definicion')->get();
    }
    public function listar_tipo_cuenta_sindicato()
    {
    	return DB::table('tipo_cuenta_sindicato')->get();
    }


    // public function insertar()
    // {
    // 	$tcs = new TipoCUentaSindicato;
    // 	//$tcs->id='4';
    // 	$tcs->descripcion = 'Caja grande';
    // 	$tcs->activo = 'S';

    // 	if($tcs->save()){
    // 		return "success";
    // 	}
    // }


    public function get_mes($id)
    {
    	 switch ($id) {
    		case '1': $mes=['id'=>$id, 'descripcion'=>'Enero' ]; break;
    		case '2': $mes=['id'=>$id, 'descripcion'=> 'Febrero']; break;
    		case '3': $mes=['id'=>$id, 'descripcion'=> 'Marzo']; break;
    		case '4': $mes=['id'=>$id, 'descripcion'=> 'Abril']; break;
    		case '5': $mes=['id'=>$id, 'descripcion'=> 'Mayo']; break;
    		case '6': $mes=['id'=>$id, 'descripcion'=> 'Junio']; break;
    		case '7': $mes=['id'=>$id, 'descripcion'=> 'Julio']; break;
    		case '8': $mes=['id'=>$id, 'descripcion'=> 'Agosto']; break;
    		case '9': $mes =['id'=>$id, 'descripcion'=> 'Septiembre']; break;
    		case '10': $mes =['id'=>$id, 'descripcion'=> 'Octubre'];  break;
    		case '11': $mes =['id'=>$id, 'descripcion'=> 'Noviembre']; break;
    		case '12': $mes =['id'=>$id, 'descripcion'=> 'Diciembre'];  break;
    		
    		
    	}

    	return $mes;
    }
}
