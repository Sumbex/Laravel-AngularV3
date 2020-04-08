<?php

namespace App;

use App\SecVotos;
use App\PortalSocio;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PortalSocioSecTemas extends Model
{
    protected function socioID()
    {
        return PortalSocio::socioLogeado()->id;
    }

    protected function verificarSocio($id)
    {
        return PortalSocio::verificarSocio($id);
    }

    protected function temasActivos()
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $temas = DB::table('sec_temas as st')
                ->select([
                    'st.id',
                    'st.fecha_inicio',
                    'st.fecha_termino',
                    DB::raw("upper(st.titulo) as titulo"),
                    DB::raw("upper(st.descripcion) as descripcion"),
                    'set.descripcion as estado',
                    'sea.descripcion as aprobado'
                ])
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
                return ['estado' => 'failed', 'mensaje' => 'No hay Temas disponibles para votar.'];
            }
        } else {
            return  $verificarSocio;
        }
    }

    protected function ingresarVoto($request)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $verificarVoto = $this->verificarVoto($request->tema);
            if ($verificarVoto == true) {
                $voto = new SecVotos;
                $voto->tema_id = $request->tema;
                $voto->socio_id = $this->socioID();
                $voto->voto_id = $request->voto;
                if ($voto->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Voto ingresado con exito.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Ya has ingresado tu voto, no puedes cambiarlo.'];
            }
        } else {
            return  $verificarSocio;
        }
    }

    protected function verificarVoto($tema)
    {
        $voto = DB::table('sec_votos')
            ->select([
                'id',
                'voto_id'
            ])
            ->where([
                'activo' => 'S',
                'tema_id' => $tema,
                'socio_id' => $this->socioID(),
                'voto_id' => 4
            ])
            ->first();
        if (!is_null($voto)) {
            return true;
        } else {
            return false;
        }
    }

    protected function traerConteoVotos($tema)
    {
        $votos = DB::table('sec_votos as sv')
            ->select([
                'sv.id',
                'sv.voto_id',
                'stv.descripcion as voto'
            ])
            ->join('sec_tipo_voto as stv', 'stv.id', 'sv.voto_id')
            ->where([
                'sv.activo' => 'S',
                'sv.tema_id' => $tema
            ])
            ->get();
        if (!$votos->isEmpty()) {
            $return = [];
            $return['apruebo'] = 0;
            $return['rechazo'] = 0;
            $return['abstengo'] = 0;
            $return['nulo'] = 0;
            foreach ($votos as $key) {
                switch ($key->voto_id) {
                    case 1:
                        $return['apruebo']++;
                        break;

                    case 2:
                        $return['rechazo']++;
                        break;

                    case 3:
                        $return['abstengo']++;
                        break;

                    case 4:
                        $return['nulo']++;
                        break;

                    default:
                        # code...
                        break;
                }
            }
            return ['estado' => 'success', 'votos' => $return, 'total' => count($votos)];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen votos.'];
        }
    }
}
