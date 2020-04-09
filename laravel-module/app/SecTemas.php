<?php

namespace App;

use App\PortalSocioSecTemas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

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

    protected function ingresarTema($request)
    {
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
            } else {
                $tema->estado_aprobacion_id = 3;
            }
            if ($tema->save()) {
                return ['estado' => 'failed', 'mensaje' => 'La votacion ha sido cerrada correctamente.'];
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
            ->get();
        if (!$temas->isEmpty()) {
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
}
