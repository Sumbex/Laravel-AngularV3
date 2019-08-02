<?php

namespace App\Http\Controllers;

use App\Socios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SocioController extends Controller
{
    public function validar_datos_socio($request)
    {
         $validator = Validator::make($request->all(), 
            [
                'rut' => 'required',
                'fecha_nacimiento' => 'required|date',
                'fecha_ingreso' => 'required|date',
                'nombres' => 'required|min:0',
                'a_paterno' => 'required',
                'a_materno' => 'required',
                //'archivo' => 'required|mimes:doc,pdf,docx',
            ],
            [
                'rut.required' => 'El rut es necesario',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es necesaria',
               // 'n_documento.unique' => 'El numero de documento ya existe en tus registros',
                'fecha_ingreso.required' => 'La fecha de ingreso es necesaria',
                'nombres.required' => 'El nombre es necesario',
                'a_paterno.required' => 'El apellido paterno es necesario',
                'a_materno.required' => 'El apellido materno es necesario'
            ]);

 
            if ($validator->fails()){ return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];}
            return ['estado' => 'success', 'mensaje' => 'success'];
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

    public function ingresar(Request $r)
    {
        $rut_limpio = $this->limpiar($r->rut);
        //dd($r->all());
    	// dd($r->file('foto'));
    	$s = new Socios;
    	// $valida_foto = $this->subir_imagen($r->file('foto'), 'storage/socios_fotos'); 

    	// if($valida_foto['estado'] == "true"){

    	// 	$s->foto = "storage/socios_fotos/".$valida_foto['nombre'];
    	// }

        $validar = $this->validar_datos_socio($r);
        $exist = Socios::where([ 'activo'=>'S','rut'=> $rut_limpio ])->get();
        //dd(count($exist)>0);
        if (count($exist)>0) {
            return ['estado'=>'failed','mensaje'=>'Este rut ya existe en la base de datos'];
        }

        if ($validar['estado'] == "success") {
        
    	
            if(!$this->valida_rut($rut_limpio)){
                return ['estado'=>'failed','mensaje'=>'Rut no valido'];
            }



        	$s->nombres = $r->nombres;
        	$s->a_paterno = $r->a_paterno;
        	$s->a_materno = $r->a_materno;
        	$s->rut = $rut_limpio;
        	$s->fecha_nacimiento = $r->fecha_nacimiento;
            $s->fecha_ingreso = $r->fecha_ingreso;
        	$s->activo = 'S';

        	if ($s->save()) {
        		return ['estado'=>'success','mensaje'=>'Socio ingresado'];
        	}

        }else{
            return ['estado'=>'failed_v', 'mensaje' => 'Error, puede que falte un dato en el formulario,o algo haya fallado, intente nuevamente'];
        }
    }

    public function listar()
    {
        return Socios::select([
            DB::raw("to_char(fecha_nacimiento, 'dd-mm-yyyy') as fecha_nacimiento_view"),
            DB::raw("to_char(fecha_ingreso, 'dd-mm-yyyy') as fecha_ingreso_view"),
            DB::raw("to_char(fecha_egreso, 'dd-mm-yyyy') as fecha_egreso_view"),
            'fecha_nacimiento',
            'fecha_ingreso',
            'fecha_egreso',

            'id',
            'rut',
            'nombres',
            'a_paterno',
            'a_materno'

        ])
        ->orderBy('fecha_ingreso','DESC')
        ->get();
    }

    public function filtrar($search='')
    {
      
        if ($search == '') {
            $this->listar();
        }
        $return = Socios::filtrar($search);
        return $return;
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


    public function actualizar_socio(Request $r)
    {

        $s = Socios::find($r->id);

        switch ($r->campo) {
            case 'rut':

           // dd(!$this->valida_rut($r->valor) == false);
                 if(!$this->valida_rut($r->valor)){
                    return ['estado'=>'failed','mensaje'=>'Rut no valido'];
                }

                $s->rut = $this->limpiar($r->valor);

                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Rut actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'nombres':
                 $s->nombres = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Nombre actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'a_paterno':
                 $s->a_paterno = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Apellido paterno actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'a_materno':
                 $s->a_materno = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Apellido materno actualizado!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'fecha_nacimiento':
                 $s->fecha_nacimiento = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Fecha de nacimiento actualizada!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'fecha_ingreso':
                $s->fecha_ingreso = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Fecha de ingreso actualizada!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;

            case 'fecha_egreso':
                $s->fecha_egreso = $r->valor;
                if($s->save()){
                    return ['estado'=>'success','mensaje' => 'Fecha de egreso actualizada!' ];
                }else{
                    return ['estado'=>'failed','mensaje' => 'Error al actualizar!' ];
                }
            break;
            
            default:
                # code...
                break;
        }
    }



    function valida_rut($rut)
    {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $dv  = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut)-1);
        $i = 2;
        $suma = 0;
        foreach(array_reverse(str_split($numero)) as $v)
        {
            if($i==8)
                $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);
        
        if($dvr == 11)
            $dvr = 0;
        if($dvr == 10)
            $dvr = 'K';
        if($dvr == strtoupper($dv))
            return true;
        else
            return false;
    }

    function limpiar($s) 
    { 
        $s = str_replace('á', 'a', $s); 
        $s = str_replace('Á', 'A', $s); 
        $s = str_replace('é', 'e', $s); 
        $s = str_replace('É', 'E', $s); 
        $s = str_replace('í', 'i', $s); 
        $s = str_replace('Í', 'I', $s); 
        $s = str_replace('ó', 'o', $s); 
        $s = str_replace('Ó', 'O', $s); 
        $s = str_replace('Ú', 'U', $s); 
        $s= str_replace('ú', 'u', $s); 

        //Quitando Caracteres Especiales 
        $s= str_replace('"', '', $s); 
        $s= str_replace(':', '', $s); 
        $s= str_replace('.', '', $s); 
        $s= str_replace(',', '', $s); 
        $s= str_replace(';', '', $s); 
        $s= str_replace('-', '', $s); 
        return $s; 
    }
}
