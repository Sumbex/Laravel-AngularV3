<?php

namespace App\Http\Controllers;

use App\Acta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActasController extends Controller
{
    public function crear(Request $r)
    {
        
        return Acta::insertar($r);
    }

    public function listar($anio_id)
    {
        $anio = $this->id_anio_to_desc($anio_id);
        // dd($anio);
        $var = Acta::where('fecha','like','%'.$anio->descripcion.'%')->orderBy('id','desc')->get();
        if ($var) {
            return [
                'estado'=>'success',
                'data' =>$var
            ];
        }
    }

    public function actualizar_acta(Request $r)
    {
        return Acta::actualizar($r);
    }

    public function actualizar_acta2(Request $r)
    {
        return Acta::actualizar2($r);
    }

    public function id_anio_to_desc($value)
    {

    	$data = DB::table('anio')->where('id', $value)->first();

    	if(!empty($data)){

    	 	return $data;
    	} 
    		
    }
}


