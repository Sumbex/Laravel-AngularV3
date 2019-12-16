<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SecAsistencia extends Model
{
    protected $table = "sec_asistencia";

    protected function traerSociosActivos()
    {
        $socios = DB::table('socios')
            ->select([
                'id',
                'rut'
            ])
            ->where([
                'activo' => 'S',
                'fecha_egreso' => null
            ])
            ->get();

        if (!$socios->isEmpty()) {
            return ['estado' => 'success', 'socios' => $socios];
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function ingresarInasistentesReunionActiva($reunion_id)
    {
        $socios = $this->traerSociosActivos();
        if ($socios['estado'] == 'success') {
            $con = 0;
            DB::beginTransaction();
            foreach ($socios['socios'] as $key) {
                $asistencia = new SecAsistencia;
                $asistencia->reunion_id = $reunion_id;
                $asistencia->socio_id = $key->id;
                $asistencia->estado_asistencia_id = 2;
                $asistencia->activo = 'S';
                $asistencia->aprobado = 'N';
                if ($asistencia->save()) {
                    $con = $con + 1;
                }
            }
            if ($con == count($socios['socios'])) {
                DB::commit();
                return ['estado' => 'success', 'ingresos' => $con];
            } else {
                DB::rollBack();
                return ['estado' => 'failed'];
            }
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function borrarInasistentesReunionActiva($reunion_id)
    {
        $borrar = SecAsistencia::where([
            'activo' => 'S',
            'reunion_id' => $reunion_id
        ]);

        if ($borrar->delete()) {
            return ['estado' => 'success'];
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function marcarAsitenciaSocio($request)
    {
        $verificarJus = DB::table('sec_justificacion')
            ->where([
                'reunion_id' => $request->reunion_id,
                'socio_id' => $request->socio_id
            ])
            ->get();

        if ($verificarJus->isEmpty()) {
            $socio = SecAsistencia::where([
                'reunion_id' => $request->reunion_id,
                'socio_id' => $request->socio_id
            ])
                ->first();
            if ($socio->estado_asistencia_id == 1) {
                return ['estado' => 'failed', 'mensaje' => 'El socio ya se encuentra marcado como asistido.'];
            } else {
                $socio->estado_asistencia_id = 1;
                if ($socio->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Socio marcado como presente correctamente.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            }
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El socio al que intentas marcar como asistido ya se encuentra justificado.'];
        }
    }

    protected function actualizarJustificadoSocio($reunion_id, $socio_id)
    {
        DB::beginTransaction();
        $socio = SecAsistencia::where([
            'reunion_id' => $reunion_id,
            'socio_id' => $socio_id
        ])
            ->first();
        $socio->estado_asistencia_id = 3;
        if ($socio->save()) {
            DB::commit();
            return ['estado' => 'success'];
        } else {
            DB::rollBack();
            return ['estado' => 'failed'];
        }
    }

    protected function traerSociosPresentes($reunion_id)
    {
        $presentes = DB::table('socios as s')
            ->select([
                's.id',
                's.rut',
                DB::raw("upper(concat(s.nombres,' ',s.a_paterno,' ',s.a_materno)) as nombre"),
                'sea.descripcion as estado'
            ])
            ->join('sec_asistencia as sa', 'sa.socio_id', 's.id')
            ->join('sec_estado_asistencia as sea', 'sea.id', 'sa.estado_asistencia_id')
            ->where([
                's.activo' => 'S',
                's.fecha_egreso' => null,
                'sa.reunion_id' => $reunion_id,
                'sa.estado_asistencia_id' => 1
            ])
            ->get();

        if (!$presentes->isEmpty()) {
            return ['estado' => 'success', 'presentes' => $presentes];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No se encuentran socios presentes actualmente.'];
        }
    }

    protected function traerListaReunion($reunion_id)
    {
        $lista = DB::table('socios as s')
            ->select([
                's.id',
                's.rut',
                DB::raw("upper(concat(s.nombres,' ',s.a_paterno,' ',s.a_materno)) as nombre"),
                'sea.descripcion as estado',
                'sa.estado_asistencia_id as estado_id'
            ])
            ->join('sec_asistencia as sa', 'sa.socio_id', 's.id')
            ->join('sec_estado_asistencia as sea', 'sea.id', 'sa.estado_asistencia_id')
            ->where([
                's.activo' => 'S',
                's.fecha_egreso' => null,
                'sa.reunion_id' => $reunion_id
            ])
            ->orderby('sea.tipo_estado', 'ASC')
            ->get();

        if (!$lista->isEmpty()) {
            $pre = 0;
            $jus = 0;
            $aus = 0;

            foreach ($lista as $key) {
                switch ($key->estado_id) {
                    case 1:
                        $pre = $pre + 1;
                        break;

                    case 2:
                        $aus = $aus + 1;
                        break;

                    case 3:
                        $jus = $jus + 1;
                        break;

                    default:
                        # code...
                        break;
                }
            }
            return ['estado' => 'success', 'lista' => $lista, 'inasistentes' => $aus, 'presentes' => $pre, 'justificados' => $jus];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No existe la lista para esta Reunión/Elección.'];
        }
    }
}
