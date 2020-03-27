<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotasCuentas extends Model
{
    protected $table = "notas";

    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'descripcion' => 'required',
            ],
            [
                'fecha.required' => 'Debes ingresar la fecha.',
                'descripcion.required' => 'Debes ingresar una descripcion.',
            ]
        );

        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function fecha($value)
    {
        $fecha = $value;
        $anio = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $anio, 'mes' => $mes, 'dia' => $dia
        ];
    }
    protected function anioID($value)
    {
        return DB::table('anio')->where('descripcion', $value)->first()->id;
    }

    protected function mesID($value)
    {
        return DB::table('mes')->where('id', $value)->first()->id;
    }

    protected function ingresarNota($request)
    {
        /* dd($request->all()); */
        $validarDatos = $this->validarDatos($request);
        if ($validarDatos['estado'] == 'success') {
            $fecha = $this->fecha($request->fecha);
            $anio = $this->anioID($fecha['anio']);
            $mes = $this->mesID($fecha['mes']);
            $nota = new NotasCuentas;
            $nota->cuenta_id = 1;
            $nota->anio_id = $anio;
            $nota->mes_id = $mes;
            $nota->dia = $fecha['dia'];
            $nota->descripcion = $request->descripcion;
            $nota->user_crea = Auth::user()->id;
            $nota->activo = "S";
            if ($nota->save()) {
                return ['estado' => 'success', 'mensaje' => 'Nota ingresada correctamente.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return $validarDatos;
        }
    }

    protected function traerNotas($anio, $mes)
    {
        $notas = DB::table('notas as n')
            ->select([
                'n.id',
                DB::raw("concat(n.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha"),
                DB::raw("upper(n.descripcion) as descripcion"),
                DB::raw("upper(concat(u.nombres,' ',u.a_paterno,' ',u.a_materno)) as nombre")
            ])
            ->join('anio as a', 'a.id', 'n.anio_id')
            ->join('mes as m', 'm.id', 'n.mes_id')
            ->join('users as u', 'u.id', 'n.user_crea')
            ->where([
                'n.cuenta_id' => 1,
                'n.anio_id' => $anio,
                'n.mes_id' => $mes,
                'n.activo' => 'S'
            ])
            ->orderby('n.dia', 'ASC')
            ->get();
        if (!$notas->isEmpty()) {
            return ['estado' => 'success', 'notas' => $notas];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay notas en la fecha seleccionada.'];
        }
    }

    protected function actualizarNota($request)
    {
        /* dd($request->all()); */
        $nota = NotasCuentas::find($request->id);
        if (!is_null($nota)) {
            $nota->descripcion = $request->descripcion;
            if ($nota->save()) {
                return ['estado' => 'success', 'mensaje' => 'Nota actualizada correctamente.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Nota no encontrada.'];
        }
    }

    protected function datosBasicos()
    {
        $datos = DB::table('users')
            ->select([
                DB::raw("current_date as fecha"),
                DB::raw("upper(concat(nombres,' ',a_paterno,' ',a_materno)) as nombre")
            ])
            ->where([
                'id' => Auth::user()->id
            ])
            ->first();

        if (!is_null($datos)) {
            return ['estado' => 'success', 'datos' => $datos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Usuario no encontrado.'];
        }
    }
}
