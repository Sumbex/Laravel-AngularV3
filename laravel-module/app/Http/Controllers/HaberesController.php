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
            'lista' => DB::table('cs_lista_haberes')->orderBy('orden','asc')->get()
        ];
    }

    public function guardar_config_haber(Request $r)
    {
        // dd($r->all());
        // try{
            // if($r->id_empleado =='' || $r->id_hab == '' || $r->valor == ''){
            //     return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
            // }
            switch ($r->tipo) {
                case 'DM': // dia * monto

                    if( $r->id_empleado =='' || 
                        $r->id_hab == '' || 
                        $r->dias == '' || 
                        $r->valor == ''){
                            return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
                        }
                    return LiqConfigHaberes::registrar($r);
                break;

                case 'DPM': //horas * porcentaje * monto
                        
                      if( $r->id_empleado =='' || 
                        $r->id_hab == '' || 
                        $r->horas == '' || 
                        $r->porcentaje == ''){
                            return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
                        }
                     return LiqConfigHaberes::registrar($r);
                break;
                case 'CM': //cargas * monto
                     if( $r->id_empleado =='' || 
                        $r->id_hab == '' || 
                        $r->cargas == '' || 
                        $r->valor == ''){
                            return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
                        }
                     return LiqConfigHaberes::registrar($r);
                break;
                

                case 'M': //monto
                    if($r->id_empleado =='' || $r->id_hab == '' || $r->valor == ''){
                        return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
                    }
                     return LiqConfigHaberes::registrar($r);
                break;

                case 'P': //porcentaje
                    if($r->id_empleado =='' || $r->id_hab == '' || $r->valor == ''){
                        return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
                    }
                     return LiqConfigHaberes::registrar($r);
                break;
                
                default:
                    # code...
                    break;
            }

            
        

        // }catch(QueryException $e){
		// 	DB::rollBack();
		// 	return[
		// 		'estado'  => 'failed', 
		// 		'mensaje' => 'QEx: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
		// 		'error' => $e
		// 	];
		// }
		// catch(\Exception $e){
		// 	DB::rollBack();
		// 	return[
		// 		'estado'  => 'failed', 
		// 		'mensaje' => 'Ex: No se ha podido seguir con el proceso de guardado, intente nuevamente o verifique sus datos',
		// 		'error' => $e
		// 	];
		// }
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
                  
                    if (trim($haber->tipo) == 'DM') {
                       
                        $update->monto = $r->valor;
                    }
                    if (trim($haber->tipo) == 'P') {
                       
                        $update->porcentaje = $r->valor;
                    }

                    if (trim($haber->tipo) == 'M') {
                        
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
