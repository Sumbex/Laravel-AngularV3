<?php

namespace App\Http\Controllers;

use App\LiqConfigHaberes;
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
            'lista' => DB::table('cs_lista_descuentos')->orderBy('orden','asc')->get()
        ];
    }

    public function guardar_config_descuento(Request $r)
    {
        try{
            if($r->id_empleado =='' || $r->id_desc == '' || $r->valor == ''){
                        return ['estado'=>'failed', 'mensaje'=>'Faltan campos por llenar'];
            }
            switch ($r->tipo) {
                case 'P':
                    
                    return LiqConfigDescuentos::registrar($r);
                break;

                case 'M':

                    return LiqConfigDescuentos::registrar($r);
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

    public function lista_conf_descuentos($empleado)
    {
        return LiqConfigDescuentos::listar($empleado);
    }

    public function monto_feriado_proporcional($empleado_id){

        $get = LiqConfigHaberes::where([
            'activo'=>'S',
            'empleado_id' => $empleado_id,
            'cs_lista_haberes_id' => 11// feriado proporcional
        ])->first();
        if ($get) {
            return (int)$get->monto;
        }else{
            return 0;
        }

    }

    public function eliminar_item_conf_desc($conf_desc_id)
    {
        $delete = LiqConfigDescuentos::where(['id'=>$conf_desc_id,'activo'=>'S'])->first();

        $delete->activo = 'N';
        if ($delete->save()) {
             // si el item es feriado prop desde descuentos
                    if($delete->cs_lista_descuentos_id == "1"||
                       $delete->cs_lista_descuentos_id == "2"||
                       $delete->cs_lista_descuentos_id == "4"
                    ){
                       
                        //en esta consulta hacemos el calculo con los 3 items (afp, salud, cesantia)
                        $fp = $this->monto_feriado_proporcional($delete->empleado_id);
                        $fer_prop=DB::select("SELECT
                            coalesce(round($fp - sum(valor)) , 0) valor
                            from(SELECT 
                                cs_lista_descuentos_id,
                                porcentaje,
                                monto,
                                ($fp * (porcentaje / 100)) valor                                        
                            FROM liq_config_descuentos des
                            inner join cs_lista_descuentos lh on lh.id = des.cs_lista_descuentos_id
                            where empleado_id = $delete->empleado_id and des.activo = 'S' and cs_lista_descuentos_id in (1,2,4)) x");

                        if(count($fer_prop) > 0){
                            
                                //en esta consulta verificamos si existe el ite feriados prop desde descuentos
                                $des_very = LiqConfigDescuentos::where([
                                    'activo'=>'S',
                                    'empleado_id'=> $delete->empleado_id,
                                    'cs_lista_descuentos_id' => 8 //feriado prop desde descuentos
                                ])->first();

                                if ($des_very) {
                                    
                                    $des_very->monto = ceil($fer_prop[0]->valor);
                                    $des_very->save();
                                }else{
                                    
                                    //si no existe, creamos el item
                                    $des = new LiqConfigDescuentos;
                                    $des->empleado_id = $delete->empleado_id;
                                    $des->cs_lista_descuentos_id = 8; //feriado prop desde descuentos
                                    $des->monto = ceil($fer_prop[0]->valor);
                                    $des->activo = 'S';
                                    $des->save();
                                }
                            }
                    }
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

    public function traer_total_h_i($empleado)
    {
        if ($empleado != '') {
            
            return LiqConfigDescuentos::tabla_i_h($empleado);
        }
    }
}
