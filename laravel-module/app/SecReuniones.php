<?php

namespace App;

use App\SecAsistencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class SecReuniones extends Model
{
    protected $table = "sec_reuniones";

    protected function traerTipoReunion()
    {
        return DB::table('sec_tipo_reunion')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();
    }

    protected function crearReunion($request)
    {
        /* $test = SecAsistencia::traerSociosActivos();
        dd(count($test['socios'])); */
        $RA = $this->traerReunionActiva();
        if ($RA['estado'] == 'failed') {
            DB::beginTransaction();
            $reunion = new SecReuniones;
            $reunion->fecha_inicio = $request->fecha_inicio;
            $reunion->estado_reunion_id = 1;
            $reunion->cabeza = $request->cabeza;
            $reunion->cuerpo = $request->cuerpo;
            $reunion->tipo_reunion_id = $request->tipo;
            $reunion->user_id = Auth::user()->id;
            $reunion->activo = 'S';
            if ($reunion->save()) {
                $asistencia = SecAsistencia::ingresarInasistentesReunionActiva($reunion->id);
                if ($asistencia['estado'] == 'success') {
                    DB::commit();
                    return ['estado' => 'success', 'mensaje' => 'Reunion creada con exito.', 'ingresos' => $asistencia['ingresos']];
                } else {
                    DB::rollBack();
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'success', 'mensaje' => 'Existe una reunion activa, recuerda finalizarla o cancelarla antes de intentar crear una nueva.'];
        }
    }

    protected function traerReunionActiva()
    {
        $reunion = DB::table('sec_reuniones as sr')
            ->select([
                'sr.id',
                'sr.fecha_inicio',
                'sr.cabeza as titulo',
                'sr.cuerpo as descripcion',
                'sr.tipo_reunion_id as tipo',
                DB::raw("concat(u.nombres,' ',u.a_paterno,' ',u.a_materno) as creada_por"),
                'sr.mod_user_id'
            ])
            ->join('users as u', 'u.id', 'sr.user_id')
            ->join('sec_estado_reunion as ser', 'ser.id', 'sr.tipo_reunion_id')
            ->where([
                'sr.activo' => 'S'
            ])
            ->whereIn('sr.estado_reunion_id', [1, 2, 3])
            ->get();

        if (!$reunion->isEmpty()) {
            if (!is_null($reunion[0]->mod_user_id)) {
                $mod = DB::table('users')
                    ->select([
                        DB::raw("concat(nombres,' ',a_paterno,' ',a_materno) as modificada_por")
                    ])
                    ->where([
                        'id' => $reunion[0]->mod_user_id
                    ])
                    ->get();
                $reunion[0]->modificada_por = $mod[0]->modificada_por;
            }
            $reunion[0]->fecha_inicio = preg_replace('/\s+/', 'T', $reunion[0]->fecha_inicio);
            return ['estado' => 'success', 'reunion' => $reunion];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no existe una reunion activa.'];
        }
    }

    protected function verificarReunionActiva($id, $tipo)
    {
        switch ($tipo) {
            case 1:
                $reunion = DB::table('sec_reuniones')
                    ->where([
                        'activo' => 'S',
                        'id' => $id
                    ])
                    ->get();

                if (!$reunion->isEmpty()) {
                    return ['estado' => 'success'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'La reunion que intentas modificar no existe o ya no esta activa.'];
                }
                break;

            case 2:
                $reunion = DB::table('sec_reuniones')
                    ->where([
                        'activo' => 'S',
                        'estado_reunion_id' => 1,
                        'id' => $id
                    ])
                    ->get();

                if (!$reunion->isEmpty()) {
                    return ['estado' => 'success'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'La reunion que intentas cancelar ya se encuentra iniciada.'];
                }
                break;

            default:
                # code...
                break;
        }
    }

    protected function modificarReunionActiva($request)
    {
        $existe = $this->verificarReunionActiva($request->id, 1);
        if ($existe['estado'] == 'success') {
            $reunion = SecReuniones::find($request->id);
            $reunion->fecha_inicio = $request->fecha_inicio;
            $reunion->cabeza = $request->cabeza;
            $reunion->cuerpo = $request->cuerpo;
            $reunion->tipo_reunion_id = $request->tipo;
            $reunion->mod_user_id = Auth::user()->id;
            if ($reunion->save()) {
                return ['estado' => 'success', 'mensaje' => 'Reunion actualizada con exito.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return $existe;
        }
    }

    protected function cancelarReunionActiva($request)
    {
        //dd($request->all());
        $existe = $this->verificarReunionActiva($request->id, 2);
        if ($existe['estado'] == 'success') {
            $reunion = SecReuniones::find($request->id);
            $reunion->estado_reunion_id = 6;
            $reunion->mod_user_id = Auth::user()->id;
            if ($reunion->save()) {
                $borrar = SecAsistencia::borrarInasistentesReunionActiva($reunion->id);
                if ($borrar['estado'] == 'success') {
                    return ['estado' => 'success', 'mensaje' => 'Reunion cancelada con exito.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return $existe;
        }
    }
}
