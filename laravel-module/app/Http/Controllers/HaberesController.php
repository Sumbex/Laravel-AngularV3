<?php

namespace App\Http\Controllers;

use App\LiqConfigHaberes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class HaberesController extends Controller
{
    public function lista_haberes()
    {
        return [
            'status' => 'success',
            'lista' => DB::table('cs_lista_haberes')->get()
        ];
    }

    public function guardar_config_haber(Request $r)
    {
        try{
            if($r->id_empleado =='' || $r->id_hab == '' || $r->valor == ''){
                return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
            }

            return LiqConfigHaberes::registrar($r);
        

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

    public function lista_conf_haberes($empleado)
    {
        return LiqConfigHaberes::listar($empleado);
    }

    public function eliminar_item_conf_hab($conf_haber_id)
    {
        $delete = LiqConfigHaberes::where(['id'=>$conf_haber_id,'activo'=>'S'])->first();

        $delete->activo = 'N';
        if ($delete->save()) {
            return ['estado'=>'success', 'mensaje'=>'item eliminado!'];
        }
        return ['estado'=>'failed', 'mensaje'=>'No se pudo eliminar el item'];
    }

    public function actualizar_conf_haber(Request $r)
    {
        try{
            $update = LiqConfigHaberes::find($r->id);
            $haber = DB::table("cs_lista_haberes")->where([
                'activo'=>'S',
                'id' => $update->cs_lista_haberes_id
            ])->first();
            
            switch ($r->campo) {
                case 'valor':
                  
                    if ($haber->tipo == 'P') {
                       
                        $update->porcentaje = $r->valor;
                    }
                    if ($haber->tipo == 'M') {
                        
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
