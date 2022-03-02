<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VacDiasBasicosDevengados extends Model
{
    protected $table = "vac_dias_basicos_devengados";

    protected function registrar($r, $condicion)
    {
        $datos = [
            'anio'=> $this->anio_actual(),
            'mes'=> $this->mes_actual()
        ];
        $anio = $datos['anio']->original->descripcion;
        $mes  = $datos['mes']['id'];

        // formulario inicial de historial del trabajador
        if($condicion=='historial'){
            $dbd = $this;
            $dbd->vac_historial_id = $r->id;
            $dbd->anio = $anio;
            $dbd->mes = $mes;
            $dbd->dias_basicos = $r->d_basicos_devengados;
            // aqui se iran registrando los dias solicitados para luego restarlos a los dias basicos
            // $dbd->dias_progresivos = $r->d_progresivos_devengados;
            $dbd->activo = 'S';

            if($dbd->save()){
                return true;
            }
            return false;
        }
        // formulario solicitud de vacaciones
        if($condicion=='solicitud'){

            if((int)$r->dias_legales != 0){
                $dbd = $this;
                $dbd->vac_historial_id = $r->vac_historial_id;
                $dbd->vac_solicitud_id = $r->id;
                $dbd->anio = $anio;
                $dbd->mes = $mes;
                $dbd->dias_progresivos = $r->dias_legales;
                // aqui se iran registrando los dias solicitados para luego restarlos a los dias basicos
                // $dbd->dias_progresivos = $r->d_progresivos_devengados;
                $dbd->activo = 'S';

                if($dbd->save()){
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;

    }


    protected function detectar_incremento_de_dias_basicos($id_vac_historial){
        // datos en base de datos
        $registro_actual = $this->get()->last();
        // return $registro_actual;


        // datos actuales fecha
        $datos = [
            'anio'=> $this->anio_actual(),
            'mes'=> $this->mes_actual()
        ];
        $anio = $datos['anio']->original->descripcion;
        $mes  = $datos['mes']['id'];

        // return $registro_actual->mes.' == '.$mes.' = '.($registro_actual->mes == $mes);
        if($registro_actual->mes != $mes){
            // ('Ya se puede actualizar el dias basico devengado');
            $incremetar = $this->incrementar_dias_bdv($id_vac_historial, $anio, $mes);
            if($incremetar){
                // return $this->sum('dias_basicos')->where('id',$id_vac_historial)->get();
                return DB::select("SELECT sum(coalesce(dias_basicos,0)) dias_basicos,
                sum(coalesce(dias_progresivos, 0)) dias_progresivos
                                 from vac_dias_basicos_devengados where vac_historial_id = ?",
                        [$id_vac_historial]);
            }


        }else{
            //('Aun no se puede actualizar el dia basico devengado');
            // return $this->sum('dias_basicos')->where('id',$id_vac_historial)->get();

            return DB::select("SELECT sum(coalesce(dias_basicos,0)) dias_basicos,
            sum(coalesce(dias_progresivos, 0)) dias_progresivos
                             from vac_dias_basicos_devengados where vac_historial_id = ?",
                        [$id_vac_historial]);
        }


    }

    protected function incrementar_dias_bdv($id, $anio, $mes){
        $dbd = $this;
        $dbd->vac_historial_id = $id;
        $dbd->anio = $anio;
        $dbd->mes = $mes;
        $dbd->dias_basicos = 1.25;
        // aqui se iran registrando los dias solicitados para luego restarlos a los dias basicos
        // $dbd->dias_progresivos = $r->d_progresivos_devengados;
        $dbd->activo = 'S';

        if($dbd->save()){
            return true;
        }
        return false;
    }

    protected function historial_mensaul_dias_basicos_devengados($vac_historial_id){
        $listado = DB::select("SELECT * FROM vac_dias_basicos_devengados where vac_historial_id = ? order by anio desc ,
        mes desc",
         [$vac_historial_id]);

         if(count($listado)>0){
            return ['estado'=>true,'listado'=>$listado ];
         }
         return ['estado'=>false, 'listado'=>[]];
    }





    public function anio_actual()
    {

        $anio = DB::select("select date_part('year',now()) as anio");
        $anio_db = DB::table('anio')->select(['id', 'descripcion'])
            ->where(['activo' => 'S', 'descripcion' => $anio[0]->anio])->first();
        return response()->json($anio_db);
    }
    public function mes_actual()
    {
        $mes_actual = DB::select("select date_part('month',now()) as mes");
        $id = $mes_actual[0]->mes;
        return $this->get_mes($id);
    }

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
