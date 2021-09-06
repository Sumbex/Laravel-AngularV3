<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentacionSindical;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentacionSindicalController extends Controller
{
    public function validar_pdf($request)
	{
		$val = Validator::make($request->all(), 
		 	[

	            'archivo' => 'required|mimes:pdf',
	        ],
	        [
	        	'archivo.required' => 'El PDF es necesario',
	        	'archivo.mimes' => 'El archivo no es PDF',
	        ]);

 
	        if ($val->fails()){ return ['estado' => 'failed_v', 'mensaje' => $val->errors()];}
	        return ['estado' => 'success', 'mensaje' => 'success'];
	}

    public function insert(Request $r){

        $valida_pdf = $this->validar_pdf($r);
       

        if($valida_pdf["estado"] == 'success'){
            $doc = new DocumentacionSindical();
            $file = $this->guardarArchivo($r->archivo,'documentacion_sindical/');

            if($file['estado'] == "success"){
                $archivo = $file['archivo'];
                $doc->titulo = $r->titulo;
                $doc->documento = 'storage/'.$archivo;
                $doc->tipo_documento = $r->tipo_documento;
                $doc->activo = 'S';
                if($doc->save()){
                    return [
                        'estado'=>true,
                        'mensaje'=>'Documentación ingresada correctamente'
                    ];
                }
                $borrar = Storage::delete('/'.$archivo);
                return [
                    'estado'=>false,
                    'mensaje'=>'No se ha podido ingresar la documentación'
                ];
            }else{
                // $borrar = Storage::delete('/'.$archivo);
                return ['estado'=>'failed','mensaje'=>'el archivo no se subio correctamente'];
            }
        }else{
            return "todo mal";
        }
        
    } 

    public function list($tipo, $activo){
        if($tipo =='all'){
            $list = DB::select("SELECT
                                    ds.id,
                                    titulo,
                                    documento,
                                    descripcion,
                                    to_char(ds.created_at, 'dd/MM/yyyy') as fecha
                                from documentacion_sindical as ds
                                inner join tipo_documentacion_sindical as tds on tds.id = ds.tipo_documento where ds.activo = '$activo'");

            return $list;
        }else{

            $list =  DB::select("SELECT
                                    ds.id,
                                    titulo,
                                    documento,
                                    descripcion,
                                    to_char(ds.created_at, 'dd/MM/yyyy') as fecha
                                from documentacion_sindical as ds
                                inner join tipo_documentacion_sindical as tds on tds.id = ds.tipo_documento
                                where tipo_documento = $tipo and ds.activo = '$activo'");

            
            return $list;
        }
        
    }

    public function tipo_documentacion_sindical(){
        return DB::table("tipo_documentacion_sindical")->get();
    }

    protected function guardarArchivo($archivo, $ruta)
    {
    	try{
	        $filenameext = $archivo->getClientOriginalName();
	        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
	        $extension = $archivo->getClientOriginalExtension();
	        $nombreArchivo = $filename . '_' . time() . '.' . $extension;
	        $rutaDB = $ruta . $nombreArchivo;

	        $guardar = Storage::put($ruta . $nombreArchivo, (string) file_get_contents($archivo), 'public');

	        if ($guardar) {
	            return ['estado' =>  'success', 'archivo' => $rutaDB];
	        } else {
	            return ['estado' =>  'failed', 'mensaje' => 'error al guardar el archivo.'];
	        }
	    }catch (\Throwable $t) {
    			return ['estado' =>  'failed', 'mensaje' => 'error al guardar el archivo, posiblemente este dañado o no exista.'];
		}
    }

    public function suspender($doc){
        $s = DocumentacionSindical::find($doc);
        $s->activo='N';
        if($s->save()){
            return [
                'estado'=>true,
                'mensaje'=>'Documento suspendido!'
            ];
        }
        return [
            'estado'=>false,
            'mensaje'=>'No se ha podido suspender el documento'
        ];
    }

    public function activar($doc){
        $s = DocumentacionSindical::find($doc);
        $s->activo='S';
        if($s->save()){
            return [
                'estado'=>true,
                'mensaje'=>'Documento activado!'
            ];
        }
        return [
            'estado'=>false,
            'mensaje'=>'No se ha podido activar el documento'
        ];
    }
}
