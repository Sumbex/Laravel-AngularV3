<?php

namespace App;

use App\SecVotos;
use Carbon\Carbon;
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
                return ['estado' => 'failed', 'mensaje' => 'No hay Temas disponibles para votar.'];
            }
        } else {
            return  $verificarSocio;
        }
    }

    protected function ingresarVoto($request)
    {
        /* dd($this->socioID()); */
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $verificarVoto = $this->verificarVoto($request->tema);
            /*  dd($verificarVoto); */
            if ($verificarVoto['estado'] == 'success') {
                $voto = SecVotos::find($verificarVoto['id']);
                if (!is_null($voto)) {
                    $voto->voto_id = $request->voto;
                    $voto->activo = 'S';
                    if ($voto->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Voto ingresado con exito.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente 1.'];
                }
            } else {
                return $verificarVoto;
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

        /* dd($voto); */
        if (!is_null($voto)) {
            return ['estado' => 'success', 'id' => $voto->id];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Ya has ingresado tu voto, no puedes cambiarlo.'];
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
            list($keys, $values) = array_divide($return);
            return ['estado' => 'success', 'votos' => $return, 'total' => count($votos), 'grafico' => $values];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen votos.'];
        }
    }

    protected function traerTipos()
    {
        return SecTemas::traerTiposTemas();
    }

    protected function traerHistorial($anio, $mes, $tipo)
    {
        return SecTemas::traerTemas($anio, $mes, $tipo);
    }

    protected function traerTipoVotos()
    {
        $votos = DB::table('sec_tipo_voto')
            ->select([
                'id',
                'descripcion as voto'
            ])
            ->get();
        if (!$votos->isEmpty()) {
            return ['estado' => 'success', 'votos' => $votos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existen tipo de votos.'];
        }
    }
}
