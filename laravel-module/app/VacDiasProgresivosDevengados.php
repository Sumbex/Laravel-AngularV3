<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VacDiasProgresivosDevengados extends Model
{
    protected $table = "vac_dias_progresivos_devengados";
    // public $id;
    // public $vac_historial_id;
    // public $anio;
    // public $dias_progresivos_devengados;
    // public $dias_progresivos_solicitud;
    // public $activo='S';

    protected function registrar($r, $condicion)
    {

        $datos = [
            'anio' => $this->anio_actual(),
            'mes' => $this->mes_actual()
        ];
        $anio = $datos['anio']->original->descripcion;
        $mes  = $datos['mes']['id'];


        if ($condicion == 'historial') {

            $dpd = $this;
            $dpd->vac_historial_id = $r->id;
            $dpd->anio = $anio;
            $dpd->mes = $mes;
            $dpd->dias_progresivos_devengados = $r->d_progresivos_devengados;
            $dpd->activo = 'S';

            if ($dpd->save()) {

                return ['estado'=> true, 'mensaje'=>'Dias agregados correctamente'];
                // return true;
            }
            return ['estado'=> false, 'mensaje'=>'No se ha podido registrar dias progresivos'];
            // return false;
        }
        if ($condicion == 'solicitud') {
            if ((int)$r->dias_progresivos > 0) {
                $dpd = $this;
                $dpd->vac_historial_id = $r->vac_historial_id;
                $dpd->anio = $anio;
                $dpd->mes = $mes;
                $dpd->dias_progresivos_solicitud = $r->dias_progresivos;
                $dpd->activo = 'S';

                if ($dpd->save()) {

                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }


    protected function dias_progresivos_devengados_disponibles($vac_historial_id)
    {

        $query = DB::select(
            "SELECT
        sum(coalesce(dias_progresivos_devengados, 0)) dias_progresivos_devengados,
        sum(coalesce(dias_progresivos_solicitud, 0)) dias_progresivos_solicitud

    from vac_dias_progresivos_devengados where vac_historial_id = ?",
            [$vac_historial_id]
        );

        if (count($query) > 0) {
            return [
                'estado' => true,
                'valores' => $query
            ];
        }
        return [
            'estado' => false,
            'valores' => []
        ];
    }

    protected function historial_dias_progresivos_devengados($vac_historial_id)
    {
        $list = DB::select(
            "SELECT * from vac_dias_progresivos_devengados where vac_historial_id = ?",
            [$vac_historial_id]
        );

        if (count($list) > 0) {
            return [
                'estado' => true,
                'tabla' => $list
            ];
        }
        return [
            'estado' => false,
            'tabla' => []
        ];
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
            case '1':
                $mes = ['id' => $id, 'descripcion' => 'Enero'];
                break;
            case '2':
                $mes = ['id' => $id, 'descripcion' => 'Febrero'];
                break;
            case '3':
                $mes = ['id' => $id, 'descripcion' => 'Marzo'];
                break;
            case '4':
                $mes = ['id' => $id, 'descripcion' => 'Abril'];
                break;
            case '5':
                $mes = ['id' => $id, 'descripcion' => 'Mayo'];
                break;
            case '6':
                $mes = ['id' => $id, 'descripcion' => 'Junio'];
                break;
            case '7':
                $mes = ['id' => $id, 'descripcion' => 'Julio'];
                break;
            case '8':
                $mes = ['id' => $id, 'descripcion' => 'Agosto'];
                break;
            case '9':
                $mes = ['id' => $id, 'descripcion' => 'Septiembre'];
                break;
            case '10':
                $mes = ['id' => $id, 'descripcion' => 'Octubre'];
                break;
            case '11':
                $mes = ['id' => $id, 'descripcion' => 'Noviembre'];
                break;
            case '12':
                $mes = ['id' => $id, 'descripcion' => 'Diciembre'];
                break;
        }

        return $mes;
    }

    protected function listado_dias_progresivos($vac_historial_id)
    {


        $listado = DB::select("SELECT
        vac_historial_id,
        anio, dias_progresivos_devengados as dpd
        from vac_dias_progresivos_devengados
        where dias_progresivos_devengados is not null and vac_historial_id = ? order by id asc",
         [$vac_historial_id]);

        if(count($listado) > 0){
            return $this->modelar_total_parcial($listado);
        }
    }
    protected function modelar_total_parcial($listado){

       $json = [];
       $i=0;
        foreach ($listado as $key) {


                $json[$i]['anio'] = $key->anio;
                $json[$i]['vac_historial_id'] = $key->vac_historial_id;
                $json[$i]['dpd'] = (int)$key->dpd;
                $json[$i]['parcial'] = $json[$i]['dpd'];

            // if($i==1){
            //     $json[$i]['parcial'] = $json[$i]['dpd'] + $json[$i-1]['dpd'];

            // }
            if($i>0){
                $json[$i]['parcial'] = $json[$i-1]['parcial'] + $json[$i]['dpd'];
            }
            $i++;
        }

        return $json;

    }
}
