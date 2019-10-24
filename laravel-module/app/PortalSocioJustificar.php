<?php

namespace App;

use App\PortalSocio;
use App\SecAsistencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PortalSocioJustificar extends Model
{
    protected $table = "sec_justificacion";

    protected function socioID()
    {
        return PortalSocio::socioLogeado()->id;
    }

    protected function verificarSocio($id)
    {
        return PortalSocio::verificarSocio($id);
    }

    protected function verificarEstadoReunion($reunion_id)
    {
        $reunion = DB::table('sec_reuniones')
            ->select([
                'id',
                'estado_reunion_id as estado'
            ])
            ->where([
                'activo' => 'S',
                'id' => $reunion_id
            ])
            ->get();

        if (!$reunion->isEmpty()) {
            if ($reunion[0]->estado == 1 || $reunion[0]->estado == 2 || $reunion[0]->estado == 3) {
                return ['estado' => 'success'];
            } else {
                return ['estado' => 'failed'];
            }
        } else {
            return ['estado' => 'failed'];
        }
    }

    protected function justificarSocio($request)
    {
        $verificarSocio = $this->verificarSocio($this->socioID());
        if ($verificarSocio['estado'] == 'success') {
            $estadoReunion = $this->verificarEstadoReunion($request->reunion_id);
            if ($estadoReunion['estado'] == 'success') {
                DB::beginTransaction();
                $justificar = new SecJustificacion;
                $justificar->reunion_id = $request->reunion_id;
                $justificar->socio_id = $this->socioID();
                $justificar->descripcion = $request->descripcion;
                $justificar->tipo_justificacion_id = $request->tipo;
                $justificar->activo = 'S';
                if ($justificar->save()) {
                    $socio = SecAsistencia::actualizarJustificadoSocio($request->reunion_id, $this->socioID());
                    if ($socio['estado'] == 'success') {
                        DB::commit();
                        return ['estado' => 'success', 'mensaje' => 'Su justificacion ha sido ingresada correctamente.'];
                    } else {
                        DB::rollBack();
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El plazo para justificarte ha finalizado.'];
            }
        } else {
            return $verificarSocio;
        }
    }
}
