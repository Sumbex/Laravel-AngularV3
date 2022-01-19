<?php

namespace App;

use App\Cuentasindicato;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Consorciopagodiasueldo extends Model
{
    protected $table = "consorcio_pago_dia_sueldo";


    protected function tabla($anio, $directiva)
    {
        $g_anio = DB::table('anio')->where(['activo'=>'S','id'=>$anio])->first();
        $anio_ = $g_anio->descripcion;

        $listar = DB::select("SELECT
                                c.id,
                                to_char(fecha,'dd/MM/yyyy') fecha,
                                concat(s.nombres,' ',s.a_paterno,' ',s.a_materno) socio,
                                descripcion,
                                monto,
                                prestamo,
                                numero_documento,
                                documento,
                                (monto + prestamo) total
                            from consorcio_pago_dia_sueldo  c
                            inner join socios s on s.id = c.socio_id
                            where  EXTRACT(YEAR FROM fecha) = '$anio_'
                            and directiva_id = $directiva
                            order by c.fecha desc
        ");

        if (count($listar) > 0) {
            return[
                'estado' => 'success',
                'data' => $listar
            ];
        }
        return [
            'estado' => 'failed',
            'mensaje'=>'No hay datos'
        ];
    }

    protected function tabla_filtro_socio($nombre, $anio, $directiva){

        $g_anio = DB::table('anio')->where(['activo'=>'S','id'=>$anio])->first();
        $anio_ = $g_anio->descripcion;

        $listar = DB::select("SELECT
                                c.id,
                                to_char(fecha,'dd/MM/yyyy') fecha,
                                concat(s.nombres,' ',s.a_paterno,' ',s.a_materno) socio,
                                descripcion,
                                monto,
                                prestamo,
                                numero_documento,
                                documento,
                                (monto + prestamo) total,
                                directiva_id
                            from consorcio_pago_dia_sueldo  c
                            inner join socios s on s.id = c.socio_id
                            where  EXTRACT(YEAR FROM fecha) = '$anio_'
                            and directiva_id = $directiva
                            and upper(concat(s.nombres,' ',s.a_paterno,' ',s.a_materno)) like upper('%$nombre%')
                            order by c.fecha desc
        ");

        if (count($listar) > 0) {
            return[
                'estado' => 'success',
                'data' => $listar
            ];
        }
        return [
            'estado' => 'failed',
            'mensaje'=>'No hay datos'
        ];
    }

    protected function item_cpds($anio, $mes)// item de egreso en cuenta sidnical
    {
        $directiva = DB::table('directiva')->where('activo', 'S')->first();
        $dir_id = (!$directiva)? 0 : $directiva->id ;

        $g_anio = DB::table('anio')->where(['activo'=>'S','id'=>$anio])->first();
        $anio_ = $g_anio->descripcion;

        $item = DB::select("SELECT
                                concat('1 de ',m.descripcion,', ',a.descripcion) fecha,
                                '--' numero_documento,
                                '--' archivo_documento,
                                concat('Pago de dia de sueldos a socios - consorcio - mes ', m.descripcion,' de ', a.descripcion) as descripcion,
                                sum(suma) monto_egreso
                            from (select
                                fecha,
                                monto,
                                prestamo,
                                (monto + prestamo) suma
                                from consorcio_pago_dia_sueldo where directiva_id = $dir_id
                                and EXTRACT(MONTH FROM fecha) = '$mes'
                                and  EXTRACT(YEAR FROM fecha) = '$anio_') x
                                inner join mes m on m.id = EXTRACT(MONTH FROM fecha)
                                inner join anio a on a.id = $anio
                                group by m.descripcion , a.descripcion");

        if (count($item) > 0) {

            $verify_exist = Cuentasindicato::where([
                'anio_id' => $anio,
                'mes_id' => $mes,
                'tipo_cuenta_sindicato' => 8, // consorcio
                'definicion' => 2, // egreso total
                'activo' => 'S'
            ])->first();

            if ($verify_exist) {
                $verify_exist->monto_egreso = $item[0]->monto_egreso;
                $verify_exist->save();
            }
            else{
                $cs = new Cuentasindicato;
                $cs->numero_documento = $item[0]->numero_documento;
                $cs->dia = '1';
                $cs->anio_id = $anio;
                $cs->mes_id = $mes;
                $cs->activo = 'S';
                $cs->tipo_cuenta_sindicato = 8;
                $cs->definicion = 2;
                $cs->archivo_documento = $item[0]->archivo_documento;
                $cs->descripcion = $item[0]->descripcion;
                $cs->monto_egreso = $item[0]->monto_egreso;
                $cs->user_crea = 0; // nadie, automatico
                $cs->save();

            }




        }




    }
}
