<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ActividadesDirectorio extends Model
{
    protected $table = "sec_actividades_directorio";

    protected function anio($value)
    {
        return DB::table('anio')->where('id', $value)->first()->descripcion;
    }

    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'responsable' => 'required',
                'actividad' => 'required'
            ],
            [
                'fecha.required' => 'Debes ingresar la fecha.',
                'responsable.required' => 'Debes ingresar al responsable.',
                'actividad.required' => 'Debes ingresar la actividad.',
            ]
        );
        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function ingresarActividad($request)
    {
        $validarDatos = $this->validarDatos($request);
        if ($validarDatos['estado'] == 'success') {
            $actividad = new ActividadesDirectorio();
            $actividad->fecha = $request->fecha;
            $actividad->responsable = $request->responsable;
            $actividad->actividad = $request->actividad;
            $actividad->estado_actividad_id = 1;
            $actividad->user_id = Auth::user()->id;
            $actividad->activo = "S";
            if ($actividad->save()) {
                return ['estado' => 'success', 'mensaje' => 'Actividad ingresada correctamente.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return $validarDatos;
        }
    }

    protected function cambiarEstadoActividad($request)
    {
        $actividad = ActividadesDirectorio::find($request->id);
        if (!is_null($actividad)) {
            if ($actividad->estado_actividad_id == 3) {
                return ['estado' => 'failed', 'mensaje' => 'La actividad ya esta ejecutada, no puedes cambiar su estado.'];
            } else {
                switch ($request->estado_actividad_id) {
                    case 1:
                        $actividad->estado_actividad_id = $request->estado_actividad_id;
                        if ($actividad->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Estado actualizado correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;
                    case 2:
                        $actividad->estado_actividad_id = $request->estado_actividad_id;
                        if ($actividad->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Estado actualizado correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;
                    case 3:
                        $actividad->estado_actividad_id = $request->estado_actividad_id;
                        if ($actividad->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Estado actualizado correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
        }
    }

    protected function traerActividades($anio, $mes)
    {
        $anioD = $this->anio($anio);
        $actividades = DB::table('sec_actividades_directorio as sad')
            ->select([
                'sad.id',
                'sad.fecha',
                'sad.responsable',
                'sad.actividad',
                'sad.estado_actividad_id',
                'sead.descripcion as estado'
            ])
            ->join('sec_estado_actividades_directorio as sead', 'sead.id', 'sad.estado_actividad_id')
            ->where([
                'sad.activo' => 'S'
            ])
            ->whereRaw("extract(year from sad.fecha) ='" . $anioD . "'and extract(month from sad.fecha) ='" . $mes . "'")
            ->orderByDesc('sad.fecha')
            ->get();
        if (!$actividades->isEmpty()) {
            foreach ($actividades as $key) {
                /* setlocale(LC_TIME, 'es'); */
                setlocale(LC_TIME, 'es_CL.UTF-8');
                $key->fecha = Carbon::parse($key->fecha)->formatLocalized('%d de %B del %Y');
            }
            return ['estado' => 'success', 'actividades' => $actividades];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay actividades creadas en la fecha seleccionada.'];
        }
    }

    protected function traerTiposActividades()
    {
        $tipos = DB::table('sec_estado_actividades_directorio')
            ->select([
                'id',
                'descripcion'
            ])
            ->get();
        if (!$tipos->isEmpty()) {
            return ['estado' => 'success', 'tipos' => $tipos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay Tipos creados.'];
        }
    }
}
