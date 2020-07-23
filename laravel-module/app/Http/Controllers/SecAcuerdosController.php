<?php

namespace App\Http\Controllers;

use App\SecAcuerdos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SecAcuerdosController extends Controller
{

    // VALIDADOR
    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'titulo' => 'required',
                'fecha' => 'required',
                'contenido' => 'required',
                'idTipoAcuerdo' => 'required'
            ],
            [
                'titulo.required' => 'Debe de escribir un titulo',
                'fecha.required' => 'Debe de seleccionar una fecha',
                'contenido.required' => 'Debe de agregar contenido al acuerdo',
                'id_tipo_acuerdo' => 'Debe de seleccionar el tipo de acuerdo.'
            ]
        );


        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    // CREAR ACUERDO
    public function setACuerdo(Request $request)
    {
        // Validacion
        $validarDatos = $this->validarDatos($request);
        if ($validarDatos['estado'] == 'success') {
            $acuerdo = new SecAcuerdos;
            $acuerdo->titulo = $request->titulo;
            $acuerdo->fecha = $request->fecha;
            $acuerdo->contenido = $request->contenido;
            $acuerdo->descripcion_cambio_estado = $request->descripcionCambioEstado;
            $acuerdo->id_tipo_acuerdo = $request->idTipoAcuerdo;
            $acuerdo->id_estado_acuerdo = 0;
            $acuerdo->id_usuario = Auth::user()->id;
            $acuerdo->activo = 'S';
            if ($acuerdo->save()) {
                return ['estado' => 'success', 'mensaje' => 'Acuerdo ingresado correctamente'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Ha ocurrido un error al ingresar el Acuerdo, por favor intente nuevamente'];
            }
        } else {
            return $validarDatos;
        }
    }

    // OBTENER TODOS LOS ACUERDOS
    public function getAcuerdos($anio)
    {
        $busqueda = DB::table('sec_acuerdos as sa')
            ->select('sa.*')
            ->orderBy('sa.id', 'desc')
            ->where('sa.activo', 'S')
            ->whereRaw("extract(year from sa.fecha) ='" . $anio . "'")
            ->get();
        if (!$busqueda->isEmpty()) {
            foreach ($busqueda as $key) {
                /* setlocale(LC_TIME, 'es'); */
                setlocale(LC_TIME, 'es_CL.UTF-8');
                $key->nuevo = Carbon::parse($key->fecha)->formatLocalized('%d %B del %Y');
            }
            return ['estado' => 'success', 'acuerdos' => $busqueda];
        } else {
            return ['estado' => 'failed_unr', 'mensaje' => 'No se han encontrado acuerdos en la fecha solicitada'];
        }
    }

    // OBTENER ACUERDOS POR ID
    public function getAcuerdoPorId($id)
    {
        $busqueda = DB::table('sec_acuerdos as sa')
            ->select('sa.*', 'e.nombres as nombre')
            ->join('users as e', 'e.id', '=', 'sa.id_usuario')
            ->where('sa.id', $id)
            ->where('sa.activo', 'S')
            ->get();
        if (!$busqueda->isEmpty()) {
            setlocale(LC_TIME, 'es_CL.UTF-8');
            $busqueda[0]->nuevo = Carbon::parse($busqueda[0]->fecha)->formatLocalized('%d %B del %Y');
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
