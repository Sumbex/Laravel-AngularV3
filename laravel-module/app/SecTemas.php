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

    protected function cerrarTema($request)
    {
        $tema = SecTemas::find($request->tema);
        if (!is_null($tema)) {
            $votos = PortalSocioSecTemas::traerConteoVotos($request->tema);
            $apr = bcdiv(($votos['votos']->apruebo * 100) / $votos['total'], '1', 2);
            $rec = bcdiv(($votos['votos']->rechazo * 100) / $votos['total'], '1', 2);
            $abs = bcdiv(($votos['votos']->abstengo * 100) / $votos['total'], '1', 2);
            $nul = bcdiv(($votos['votos']->nulo * 100) / $votos['total'], '1', 2);
            $tema->estado_tema_id = 2;
            if ($votos['votos']->apruebo > $votos['votos']->rechazo) {
                $tema->estado_aprobacion_id = 2;
                $total = $apr + $abs;
                $tema->motivo = "Se ha aprovado con un " . $total . "%, de un total de: " . $votos['total'] . "votos de los cuales: " . $apr . "% fueron aprobado y " . $abs . "% fueron me abstengo, teniendo asi una cantidad de: " . $votos['votos']->nulo . " votos nulos, siendo asi un:" . $nul . "%.";
            } else {
                $tema->estado_aprobacion_id = 3;
                $total = $rec + $abs;
                $tema->motivo = "Se ha rechazado con un " . $total . "%, de un total de: " . $votos['total'] . "votos de los cuales: " . $rec . "% fueron rechazado y " . $abs . "% fueron me abstengo, teniendo asi una cantidad de: " . $votos['votos']->nulo . " votos nulos, siendo asi un:" . $nul . "%.";
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

    protected function traerTemas($anio, $mes, $tipo)
    {
        $votos = PortalSocioSecTemas::traerConteoVotos(1);
        $porc = $this->converPorc($votos['votos'], $votos['total']);
        dd($porc);
        $anioD = $this->anio($anio);
        $temas = DB::table('sec_temas as st')
            ->select([
                'st.id',
                'st.fecha_inicio',
                'st.fecha_termino',
                DB::raw("upper(st.titulo) as titulo"),
                DB::raw("upper(st.descripcion) as descripcion"),
                'st.estado_tema_id',
                'set.descripcion as estado',
                'sea.descripcion as aprobado'
            ])
            ->join('sec_estado_tema as set', 'set.id', 'st.estado_tema_id')
            ->join('sec_estado_aprobacion as sea', 'sea.id', 'st.estado_aprobacion_id')
            ->where([
                'st.activo' => 'S'
            ])
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
                return ['estado' => 'success', 'temas' => $return];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay Temas creados en la fecha ingresada.'];
        }
    }
}
