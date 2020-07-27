<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActividadesDirectorio extends Model
{
    protected $table = "sec_actividades_directorio";

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

    protected function traerActividadesActivas()
    {
        $activas = DB::table('sec_actividades_directorio as sad')
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
            ->get();
        if (!$activas->isEmpty()) {
            return ['estado' => 'success', 'activas' => $activas];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay actividades activas.'];
        }
    }
}
