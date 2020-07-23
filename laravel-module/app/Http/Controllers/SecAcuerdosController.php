<?php

namespace App\Http\Controllers;

use App\SecAcuerdos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SecAcuerdosController extends Controller
{
    // CREAR ACUERDO
    public function setACuerdo(Request $request){
        $acuerdo = new SecAcuerdos;
        $acuerdo->titulo = $request->titulo;
        $acuerdo->fecha = $request->fecha;
        $acuerdo->contenido = $request->contenido;
        $acuerdo->descripcion_cambio_estado = $request->descripcionCambioEstado;
        $acuerdo->id_tipo_acuerdo = $request->idTipoAcuerdo;
        $acuerdo->id_estado_acuerdo = 0;
        $acuerdo->id_usuario = Auth::user()->id;
        $acuerdo->activo = 'S';
        if($acuerdo->save()){
            return ['estado' => 'success', 'mensaje' => 'Acuerdo ingresado correctamente'];
        }else{
            return ['estado' => 'failed', 'mensaje' => 'Ha ocurrido un error al ingresar el Acuerdo, por favor intente nuevamente'];
        }
    }

    // OBTENER TODOS LOS ACUERDOS
    public function getAcuerdos(){
        $busqueda = DB::table('sec_acuerdos as sa')
            ->select('sa.*')
            ->orderBy('fecha', 'desc')
            ->get();
        if (!$busqueda->isEmpty()) {
            foreach ($busqueda as $key) {
                /* setlocale(LC_TIME, 'es'); */
                setlocale(LC_TIME, 'es_CL.UTF-8');
                $key->nuevo = Carbon::parse($key->fecha)->formatLocalized('%d %B del %Y');
            }
            return ['estado' => 'success', 'acuerdos' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado acuerdos con el empleado solicitado'];
        }
    }

    // OBTENER ACUERDOS POR ID
    public function getAcuerdoPorId($id)
    {
        $busqueda = DB::table('sec_acuerdos as sa')
            ->select('sa.*', 'e.nombres as nombre')
            ->join('users as e', 'e.id', '=', 'sa.id_usuario')
            ->where('sa.id', $id)
            ->orderBy('fecha', 'desc')
            ->get();
        if (!$busqueda->isEmpty()) {
            return ['estado' => 'success', 'acuerdo' => $busqueda[0]];
            } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado el acuerdo por el id solicitado'];
        }
    }

    // ACTUALIZAR ACUERDO
    public function updateAcuerdo(Request $request)
    {
        $acuerdo = SecAcuerdos::find($request->id);
            if (!is_null($acuerdo)) {
                $acuerdo->titulo = $request->titulo;
                $acuerdo->fecha = $request->fecha;
                $acuerdo->contenido = $request->contenido;
                $acuerdo->descripcion_cambio_estado = $request->descripcion_cambio_estado;
                $acuerdo->id_tipo_acuerdo = $request->id_tipo_acuerdo;
                $acuerdo->id_estado_acuerdo = $request->id_estado_acuerdo;
                if ($acuerdo->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Acuerdo actualizado correctamente'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Se ha producido un error al actualizar el acuerdo.'];
                }
            }
    }
}
