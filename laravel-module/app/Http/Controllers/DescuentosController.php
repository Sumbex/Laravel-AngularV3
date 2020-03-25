<?php

namespace App\Http\Controllers;

use App\LiqConfigDescuentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DescuentosController extends Controller
{
    
    public function lista_descuentos()
    {
        return [
            'status' => 'success',
            'lista' => DB::table('cs_lista_descuentos')->get()
        ];
    }

    public function guardar_config_descuento(Request $r)
    {
        try{
            if($r->id_empleado =='' || $r->id_desc == '' || $r->valor == ''){
                return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
            }

            return LiqConfigDescuentos::registrar($r);
        

        }catch(QueryException $e){
			DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
				'error' => $e
			];
		}
		catch(\Exception $e){
			DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
				'error' => $e
			];
		}
    }

    public function lista_conf_descuentos($empleado)
    {
        return LiqConfigDescuentos::listar($empleado);
    }

    public function eliminar_item_conf_desc($conf_desc_id)
    {
        $delete = LiqConfigDescuentos::where(['id'=>$conf_desc_id,'activo'=>'S'])->first();

        $delete->activo = 'N';
        if ($delete->save()) {
            return ['estado'=>'success', 'mensaje'=>'item eliminado!'];
        }
        return ['estado'=>'failed', 'mensaje'=>'No se pudo eliminar el item'];
    }
    
    public function actualizar_conf_descuento(Request $r)
    {
        try{
            $update = LiqConfigDescuentos::find($r->id);
            $desc = DB::table("cs_lista_descuentos")->where([
                'activo'=>'S',
                'id' => $update->cs_lista_descuentos_id
            ])->first();
            
            switch ($r->campo) {
                case 'valor':
                  
                    if ($desc->tipo == 'P') {
                       
                        $update->porcentaje = $r->valor;
                    }
                    if ($desc->tipo == 'M') {
                        
                        $update->monto = $r->valor;
                    }
                    if ($update->save()) {
                        return ['estado'=>'success', 'mensaje'=>'Valor actualizado'];
                    }
                    return ['estado'=>'failed', 'mensaje'=>'Error al actualizar'];
                
                break;
                
                default:
                    # code...
                    break;
            }


        }catch(QueryException $e){
			DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'QEx: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
				'error' => $e
			];
		}
		catch(\Exception $e){
			DB::rollBack();
			return[
				'estado'  => 'failed', 
				'mensaje' => 'Ex: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
				'error' => $e
			];
		}
    }
}
