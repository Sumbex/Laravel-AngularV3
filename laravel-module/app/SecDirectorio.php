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
                $r->acuerdos=$datos->acuerdos;
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
                    if ($datos->primerDirector == 'true') {
                        $primerDirector = new SecDirectivaAsistida;
                        $primerDirector->sec_directorio_id = $r->id;
                        $primerDirector->sec_directiva_id = '4';
                        $primerDirector->activo = 'S';
                        $primerDirector->save();
                    }
                    if ($datos->segundoDirector == 'true') {
                        $segundoDirector = new SecDirectivaAsistida;
                        $segundoDirector->sec_directorio_id = $r->id;
                        $segundoDirector->sec_directiva_id = '5';
                        $segundoDirector->activo = 'S';
                        $segundoDirector->save();
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

    protected function visualizar_directorio($anio)
    {

        $listar = SecDirectorio::select([
                                'sec_directorio.id', 
                                'sec_directorio.fecha', 
                                'sec_directorio.titulo', 
                                'sec_directorio.asistencia', 
                                'sec_directorio.temas',
                                'sec_directorio.acuerdos', 
                                'str.descripcion as tipo',

        ])
            ->join('sec_tipo_reunion as str', 'str.id', 'sec_directorio.sec_tipo_reunion_id')
            ->orderby('sec_directorio.id', 'desc')
            ->whereRaw("extract(year from sec_directorio.fecha) ='" . $anio ."'")
            ->get();
            // dd($listar);
        if (count($listar) > 0) {
            foreach ($listar as $key) {
            //     $key->fecha = Carbon::parse($key->fecha)->format('d/m/Y');
            setlocale(LC_TIME, 'es_CL.UTF-8');
                $key->nuevo = Carbon::parse($key->fecha)->formatLocalized('%d %B del %Y');
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
