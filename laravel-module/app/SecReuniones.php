<?php

namespace App;

use Carbon\Carbon;
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
        $reunion = SecReuniones::where([
            'activo' => 'S'
        ])
            ->get()->last();
        /* dd($reunion); */
        if (!is_null($reunion)) {
            if ($reunion->estado_reunion_id == 5 || $reunion->estado_reunion_id == 6) {
                return $this->ingresoReunion($request);
            } else {
                return ['estado' => 'success', 'mensaje' => 'Existe una reunion activa, recuerda archivarla o cancelarla antes de intentar crear una nueva.'];
            }
        } else {
            return $this->ingresoReunion($request);
        }
    }

    protected function ingresoReunion($request)
    {
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
            ->whereIn('sr.estado_reunion_id', [1, 2])
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

    protected function cerrarReunionActiva($request)
    {
        $reunion = DB::table('sec_reuniones')
            ->where([
                'activo' => 'S',
                /* 'estado_reunion_id' => 2, */
                'id' => $request->id
            ])
            ->get();

        if (!$reunion->isEmpty()) {
            $modReunion = SecReuniones::find($request->id);
            $termino = DB::select('select NOW() as fecha');
            $modReunion->fecha_termino = $termino[0]->fecha;
            $modReunion->estado_reunion_id = 3;
            $modReunion->mod_user_id = Auth::user()->id;
            if ($modReunion->save()) {
                return ['estado' => 'success', 'mensaje' => 'Reunion terminada con exito.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'La reunion.'];
        }
    }

    protected function archivarReunion($request)
    {
        $reunion = DB::table('sec_reuniones')
            ->where([
                'activo' => 'S',
                /* 'estado_reunion_id' => 2, */
                'id' => $request->id
            ])
            ->get();

        if (!$reunion->isEmpty()) {
            $modReunion = SecReuniones::find($request->id);
            $modReunion->estado_reunion_id = 5;
            $modReunion->mod_user_id = Auth::user()->id;
            if ($modReunion->save()) {
                return ['estado' => 'success', 'mensaje' => 'Reunion archivada con exito.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'La reunion.'];
        }
    }

    protected function traerReunionCerradaFinalizada()
    {
        $reunion = DB::table('sec_reuniones as sr')
            ->select([
                'sr.id',
                'sr.fecha_inicio',
                'sr.fecha_termino',
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
            ->whereIn('sr.estado_reunion_id', [3, 4])
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
            return ['estado' => 'failed', 'mensaje' => 'Aun no existe una reunion finalizada.'];
        }
    }

    protected function traerSocioPorRut($rut, $reunion_id)
    {
        $limpiar = $this->limpiarRut($rut);
        $validarRut = $this->validarRut($limpiar);
        if ($validarRut == true) {
            $socio = DB::table('socios as s')
                ->select([
                    's.id',
                    DB::raw("concat(s.nombres,' ',s.a_paterno,' ',s.a_materno) as nombre"),
                    'sea.descripcion as estado'
                ])
                ->join('sec_asistencia as sa', 'sa.socio_id', 's.id')
                ->join('sec_estado_asistencia as sea', 'sea.id', 'sa.estado_asistencia_id')
                ->where([
                    's.activo' => 'S',
                    's.rut' => $limpiar,
                    's.fecha_egreso' => null,
                    'sa.reunion_id' => $reunion_id
                ])
                ->get();
            if (!$socio->isEmpty()) {
                return ['estado' => 'success', 'socio' => $socio[0]];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El socio ya no se encuenta activo en el sindicato o no existe.'];
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no es valido.'];
        }
    }

    protected function filtrarSocio($reunion_id, $socio)
    {
        $buscar = mb_strtolower($socio);

        $datos = DB::table('socios as s')
            ->select([
                's.id',
                DB::raw("concat(s.nombres,' ',s.a_paterno,' ',s.a_materno) as nombre"),
                'sea.descripcion as estado'
            ])
            ->join('sec_asistencia as sa', 'sa.socio_id', 's.id')
            ->join('sec_estado_asistencia as sea', 'sea.id', 'sa.estado_asistencia_id')
            ->where([
                's.activo' => 'S',
                's.fecha_egreso' => null,
                'sa.reunion_id' => $reunion_id
            ])
            ->whereRaw("lower(concat(s.nombres,' ',s.a_paterno,' ',s.a_materno))', 'like', '%' . $buscar . '%")
            /* ->where('lower(CONCAT(s.nombres,' ',s.a_paterno,' ',s.a_materno))', 'like', $buscar); */
            /* ->whereRaw('lower(CONCAT(s.nombres,' ',s.a_paterno,' ',s.a_materno)) like'. . '%$busca%') */
            ->get();
        dd($datos);
        if (!$datos->isEmpty()) {
            return ['estado' => 'success', 'socio' => $datos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El socio ya no se encuenta activo en el sindicato o no existe.'];
        }
    }

    protected function validarRut($rut)
    {
        try {
            $rut = preg_replace('/[^k0-9]/i', '', $rut);
            $dv  = substr($rut, -1);
            $numero = substr($rut, 0, strlen($rut) - 1);
            $i = 2;
            $suma = 0;
            foreach (array_reverse(str_split($numero)) as $v) {
                if ($i == 8)
                    $i = 2;
                $suma += $v * $i;
                ++$i;
            }
            $dvr = 11 - ($suma % 11);

            if ($dvr == 11)
                $dvr = 0;
            if ($dvr == 10)
                $dvr = 'K';
            if ($dvr == strtoupper($dv))
                return true;
            else
                return false;
        } catch (\Exception $e) {
            return ['status' => 'failed', 'Se ha producido un error, verifique si el rut es correcto o existe en la base de datos'];
        }
    }

    protected function limpiarRut($rut)
    {
        $rut = str_replace('á', 'a', $rut);
        $rut = str_replace('Á', 'A', $rut);
        $rut = str_replace('é', 'e', $rut);
        $rut = str_replace('É', 'E', $rut);
        $rut = str_replace('í', 'i', $rut);
        $rut = str_replace('Í', 'I', $rut);
        $rut = str_replace('ó', 'o', $rut);
        $rut = str_replace('Ó', 'O', $rut);
        $rut = str_replace('Ú', 'U', $rut);
        $rut = str_replace('ú', 'u', $rut);

        //Quitando Caracteres Especiales 
        $rut = str_replace('"', '', $rut);
        $rut = str_replace(':', '', $rut);
        $rut = str_replace('.', '', $rut);
        $rut = str_replace(',', '', $rut);
        $rut = str_replace(';', '', $rut);
        $rut = str_replace('-', '', $rut);
        return $rut;
    }

    protected function marcarAsitenciaSocioReunion($request)
    {
        $verificar = DB::table('sec_reuniones')
            ->where([
                'activo' => 'S',
                'id' => $request->reunion_id
            ])
            ->whereIn('estado_reunion_id', [3, 4])
            ->get();

        if (!$verificar->isEmpty()) {
            return SecAsistencia::marcarAsitenciaSocio($request);
        } else {
            return ['estado' => 'failed', 'mensaje' => 'La reunion actual no existe o su estado no se encuentra Cerrado o Finalizado.'];
        }
    }

    protected function traerSociosPresentes($reunion_id)
    {
        return SecAsistencia::traerSociosPresentes($reunion_id);
    }

    protected function traerListaReunion($reunion_id)
    {
        return SecAsistencia::traerListaReunion($reunion_id);
    }

    protected function traerJustificacionSocio($reunion_id, $socio_id)
    {
        return SecJustificacion::traerJustificacionSocio($reunion_id, $socio_id);
    }

    protected function traerHistorialReuniones()
    {
        $reunion = DB::table('sec_reuniones as sr')
            ->select([
                'sr.id',
                'sr.fecha_inicio',
                'sr.fecha_termino',
                'sr.cabeza as titulo',
                'sr.cuerpo as descripcion',
                DB::raw("concat(u.nombres,' ',u.a_paterno,' ',u.a_materno) as creada_por"),
                'sr.mod_user_id',
                'sr.tipo_reunion_id as tipo_id',
                'str.descripcion as tipo'
            ])
            ->join('users as u', 'u.id', 'sr.user_id')
            ->join('sec_tipo_reunion as str', 'str.id', 'sr.tipo_reunion_id')
            ->where([
                'sr.activo' => 'S',
                'sr.estado_reunion_id' => 5
            ])
            ->orderBy('sr.tipo_reunion_id', 'asc')
            ->get();

        if (!$reunion->isEmpty()) {
            foreach ($reunion as $key) {
                if (!is_null($key->mod_user_id)) {
                    $mod = DB::table('users')
                        ->select([
                            DB::raw("concat(nombres,' ',a_paterno,' ',a_materno) as modificada_por")
                        ])
                        ->where([
                            'id' => $key->mod_user_id
                        ])
                        ->get();
                    $key->modificada_por = $mod[0]->modificada_por;
                }
            }

            return ['estado' => 'success', 'reuniones' => $reunion];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no existen reuniones archivadas.'];
        }
    }
}
