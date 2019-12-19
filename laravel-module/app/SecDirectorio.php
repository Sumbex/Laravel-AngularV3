<?php

namespace App;

use Carbon\Carbon;
use App\SecDirectivaAsistida;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class SecDirectorio extends Model
{
    protected $table = "sec_directorio";

    public function validar_registro_directorio($datos)
    {
        $validator = Validator::make(
            $datos->all(),
            [
                'fecha' => 'required',
                'sec_tipo_reunion_id' => 'required',
                'titulo' => 'required',
                'temas' => 'required'
            ],
            [
                'fecha.required' => 'Debe ingresar una fecha',
                'sec_tipo_reunion_id.required' => 'Debe seleccionar un tipo de reunion',
                'titulo.required' => 'Debe ingresar un titulo',
                'temas.required' => 'debe ingresar los temas a tratar',

            ]
        );

 
        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function registro_directorio($datos)
    {
        try {
            $validarDatos = $this->validar_registro_directorio($datos);

            // dd($datos);
            if ($validarDatos['estado'] == 'success') {
                $r = $this;
                $r->fecha = $datos->fecha;
                $r->sec_tipo_reunion_id = $datos->sec_tipo_reunion_id;
                $r->titulo=$datos->titulo;
                $r->asistencia=$datos->asistencia;
                $r->temas=$datos->temas;
                $r->activo='S';
                if ($r->save()) {
                    if ($datos->presidente == 'true') {
                        $presidente = new SecDirectivaAsistida;
                        $presidente->sec_directorio_id = $r->id;
                        $presidente->sec_directiva_id = '1';
                        $presidente->activo = 'S';
                        $presidente->save();
                    }
                    if ($datos->secretario == 'true') {
                        $secretario = new SecDirectivaAsistida;
                        $secretario->sec_directorio_id = $r->id;
                        $secretario->sec_directiva_id = '2';
                        $secretario->activo = 'S';
                        $secretario->save();
                    }
                    if ($datos->tesorero == 'true') {
                        $tesorero = new SecDirectivaAsistida;
                        $tesorero->sec_directorio_id = $r->id;
                        $tesorero->sec_directiva_id = '3';
                        $tesorero->activo = 'S';
                        $tesorero->save();
                    }
                   
                    return ['estado'=>'success', 'mensaje'=>'Reunion Agendada con exito!'];
                } else {
                    return ['estado'=>'failed', 'mensaje'=>'A ocurrido un error, verifique que los campos esten correctos'];
                }
            }
            return $validarDatos;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    protected function visualizar_directorio()
    {

        // $listar2 = DB::select("SELECT
        //                         sd.id, 
        //                         sd.fecha, 
        //                         sd.titulo, 
        //                         sd.asistencia, 
        //                         sd.temas, 
        //                         str.descripcion,
        //                         sd2.descripcion
                                
        //                         from sec_directorio as sd
        //                         inner join sec_tipo_reunion as str on str.id = sd.sec_tipo_reunion_id
        //                         inner join sec_directiva_asistida as sda on sda.sec_directorio_id = sd.id
        //                         inner join sec_directiva as sd2 on sd2.id = sda.sec_directiva_id
        //                         where sd.activo = 'S' 
        //                         order by id asc");

        $listar = SecDirectorio::select([
                                'sec_directorio.id', 
                                'sec_directorio.fecha', 
                                'sec_directorio.titulo', 
                                'sec_directorio.asistencia', 
                                'sec_directorio.temas', 
                                'str.descripcion as tipo',
                                // 'sd.descripcion as directiva'

        ])
            ->join('sec_tipo_reunion as str', 'str.id', 'sec_directorio.sec_tipo_reunion_id')
            // ->join('sec_directiva_asistida as sda','sda.sec_directorio_id','sec_directorio.id')
            // ->join('sec_directiva as sd','sd.id','sda.sec_directiva_id')     
            ->orderby('sec_directorio.id', 'asc')
            ->get();
            // dd($listar);
        if (count($listar) > 0) {
            foreach ($listar as $key) {
                $key->fecha = Carbon::parse($key->fecha)->format('d/m/Y');
            }
        }

        if (!$listar->isEmpty()) {
            return ['estado'=>'success' , 'listar' => $listar];
        } else {
            return ['estado'=>'failed', 'mensaje'=>'Aun no existen datos para mostrar.'];
        }
    }
    protected function visualizar_directorio_detalle($id)
    {

        $listar = DB::select("SELECT
        
                                sec_directiva.id,
                                sec_directiva.descripcion,
                                sec_directiva.activo

                                from sec_directiva
                                inner join sec_directiva_asistida as sda on sda.sec_directiva_id = sec_directiva.id
                                where sda.activo = 'S' and sda.sec_directorio_id = $id");

            // dd($listar);
        if (count($listar) > 0) {
            return ['estado'=>'success' , 'listar' => $listar];
        } else {
            return ['estado'=>'failed', 'mensaje'=>'Aun no existen datos para mostrar.'];
        }
    }
}
