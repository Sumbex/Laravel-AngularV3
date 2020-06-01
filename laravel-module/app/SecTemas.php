<?php

namespace App;

use Carbon\Carbon;
use App\PortalSocioSecTemas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class SecTemas extends Model
{
    protected $table = "sec_temas";

    protected function anio($value)
    {
        return DB::table('anio')->where('id', $value)->first()->descripcion;
    }

    protected function fechaActual()
    {
        return NotasCuentas::datosBasicos()['datos']->fecha;
    }

    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'titulo' => 'required',
                'descripcion' => 'required',
            ],
            [
                'fecha.required' => 'Debes ingresar la fecha.',
                'titulo.required' => 'Debes ingresar un titulo.',
                'descripcion.required' => 'Debes ingresar una descripcion.',
            ]
        );

        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function ingresarTema($request)
    {
        $validarDatos = $this->validarDatos($request);
        if ($validarDatos['estado'] == 'success') {
            DB::beginTransaction();
            $tema = new SecTemas;
            $tema->fecha_inicio = $request->fecha;
            $tema->titulo = $request->titulo;
            $tema->descripcion = $request->descripcion;
            $tema->estado_tema_id = 1;
            $tema->estado_aprobacion_id = 1;
            $tema->user_id = Auth::user()->id;
            $tema->activo = "S";
            if ($tema->save()) {
                $voto = SecVotos::ingresarVotosNulos($tema->id);
                if ($voto['estado'] == "success") {
                    DB::commit();
                    return ['estado' => 'success', 'mensaje' => 'Tema publicado con exito.'];
                } else {
                    DB::rollBack();
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            } else {
                DB::rollBack();
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return $validarDatos;
        }
    }

    protected function actualizaTema($request)
    {
        $tema = SecTemas::find($request->id);
        if (!is_null($tema)) {
            $tema->fecha_inicio = $request->fecha;
            $tema->titulo = $request->titulo;
            $tema->descripcion = $request->descripcion;
            if ($tema->save()) {
                return ['estado' => 'success', 'mensaje' => 'Tema actualizado con exito.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El tema a actualizar no existe.'];
        }
    }

    protected function cerrarTema($request)
    {
        $tema = SecTemas::find($request->tema);
        if (!is_null($tema)) {
            $votos = PortalSocioSecTemas::traerConteoVotos($request->tema);
            $tema->estado_tema_id = 2;
            $tema->fecha_termino = $this->fechaActual();
            if ($votos['votos']['apruebo'] > $votos['votos']['rechazo']) {
                $tema->estado_aprobacion_id = 2;
                $total = $votos['votos']['apruebo'] + $votos['votos']['abstengo'];
                $tema->motivo = "Se ha aprobado con: " . $total . " votos a favor, de los cuales: " . $votos['votos']['apruebo'] . " fueron apruebo y " . $votos['votos']['abstengo'] . " fueron me abstengo.";
            } elseif ($votos['votos']['apruebo'] == $votos['votos']['rechazo']) {
                $tema->estado_aprobacion_id = 5;
                /* $tema->motivo = "Se ha rechazado con: " . $total . " votos en contra, de los cuales: " . $votos['votos']['rechazo'] . " fueron rechazo y " . $votos['votos']['abstengo'] . " fueron me abstengo."; */
                $tema->motivo = "La votacion no se ha concretado debido a una igualdad de votos.";
            } else {
                $tema->estado_aprobacion_id = 3;
                $total = $votos['votos']['rechazo'] + $votos['votos']['abstengo'];
                $tema->motivo = "Se ha rechazado con: " . $total . " votos en contra, de los cuales: " . $votos['votos']['rechazo'] . " fueron rechazo y " . $votos['votos']['abstengo'] . " fueron me abstengo.";
            }
            if ($tema->save()) {
                return ['estado' => 'success', 'mensaje' => 'La votacion ha sido cerrada correctamente.', 'motivo' =>  strtoupper($tema->motivo)];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El tema que intentas cerrar no existe.'];
        }
    }

    protected function cancelarTema($request)
    {
        $tema = SecTemas::find($request->tema);
        if (!is_null($tema)) {
            $tema->fecha_termino = $this->fechaActual();
            $tema->estado_tema_id = 3;
            $tema->estado_aprobacion_id = 4;
            if ($tema->save()) {
                return ['estado' => 'success', 'mensaje' => 'Tema cancelado con exito.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El tema a cancelar no existe.'];
        }
    }

    protected function traerTemasActivos()
    {
        $temas = DB::table('sec_temas as st')
            ->select([
                'st.id',
                'st.fecha_inicio',
                'st.fecha_inicio as fecha',
                DB::raw("upper(st.titulo) as titulo"),
                DB::raw("upper(st.descripcion) as descripcion"),
                'set.descripcion as estado',
                DB::raw("upper(concat(u.nombres,' ',u.a_paterno,' ',u.a_materno)) as nombre")
            ])
            ->join('users as u', 'u.id', 'st.user_id')
            ->join('sec_estado_tema as set', 'set.id', 'st.estado_tema_id')
            ->join('sec_estado_aprobacion as sea', 'sea.id', 'st.estado_aprobacion_id')
            ->where([
                'st.activo' => 'S',
                'st.estado_tema_id' => 1
            ])
            ->get();
        if (!$temas->isEmpty()) {
            foreach ($temas as $key) {
                /* setlocale(LC_TIME, 'es'); */
                setlocale(LC_TIME, 'es_CL.UTF-8');
                $key->fecha_inicio = Carbon::parse($key->fecha_inicio)->formatLocalized('%d de %B del %Y');
            }
            return ['estado' => 'success', 'temas' => $temas];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay Temas Activos.'];
        }
    }

    protected function traerTemas($anio, $mes, $tipo)
    {
        $anioD = $this->anio($anio);
        $temas = DB::table('sec_temas as st')
            ->select([
                'st.id',
                'st.fecha_inicio',
                'st.fecha_termino',
                DB::raw("upper(st.titulo) as titulo"),
                DB::raw("upper(st.descripcion) as descripcion"),
                DB::raw("upper(st.motivo) as motivo"),
                'st.estado_tema_id',
                'set.descripcion as estado',
                'sea.descripcion as aprobado',
                DB::raw("upper(concat(u.nombres,' ',u.a_paterno,' ',u.a_materno)) as nombre")
            ])
            ->join('users as u', 'u.id', 'st.user_id')
            ->join('sec_estado_tema as set', 'set.id', 'st.estado_tema_id')
            ->join('sec_estado_aprobacion as sea', 'sea.id', 'st.estado_aprobacion_id')
            ->where([
                'st.activo' => 'S'
            ])
            ->whereIn('st.estado_tema_id', [2, 3])
            ->whereRaw("extract(year from st.fecha_inicio) ='" . $anioD . "'and extract(month from st.fecha_inicio) ='" . $mes . "'")
            ->orderBy('st.fecha_inicio', 'desc')
            ->get();
        if (!$temas->isEmpty()) {
            foreach ($temas as $key) {
                /* setlocale(LC_TIME, 'es'); */
                setlocale(LC_TIME, 'es_CL.UTF-8');
                $key->fecha_inicio = Carbon::parse($key->fecha_inicio)->formatLocalized('%d de %B del %Y');
                $key->fecha_termino = Carbon::parse($key->fecha_termino)->formatLocalized('%d de %B del %Y');
            }
            if ($tipo == 0) {
                return ['estado' => 'success', 'temas' => $temas];
            } else {
                $return = [];
                foreach ($temas as $key) {
                    if ($tipo == $key->estado_tema_id) {
                        $return[] = $key;
                    }
                }
                if (!empty($return)) {
                    return ['estado' => 'success', 'temas' => $return];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'No hay Temas creados en la fecha ingresada.'];
                }
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay Temas creados en la fecha ingresada.'];
        }
    }

    protected function traerTiposTemas()
    {
        $tipos = DB::table('sec_estado_tema')
            ->select([
                'id',
                'descripcion as tipo'
            ])
            ->whereIn('id', [2, 3])
            ->get();
        if (!$tipos->isEmpty()) {
            return ['estado' => 'success', 'tipos' => $tipos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay Tipos creados.'];
        }
    }

    protected function traerVotos($tema)
    {
        return PortalSocioSecTemas::traerConteoVotos($tema);
    }
}
