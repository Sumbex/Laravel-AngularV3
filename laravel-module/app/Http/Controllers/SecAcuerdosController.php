<?php

namespace App\Http\Controllers;

use App\SecAcuerdos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecAcuerdosController extends Controller
{
    public function setACuerdo(Request $request){
        $acuerdo = new SecAcuerdos;
        $acuerdo->titulo = $request->titulo;
        $acuerdo->fecha = $request->fecha;
        $acuerdo->contenido = $request->contenido;
        $acuerdo->descripcion_cambio_estado = $request->descripcionCambioEstado;
        $acuerdo->id_tipo_acuerdo = $request->idTipoAcuerdo;
        $acuerdo->id_estado_acuerdo = $request->idEstadoAcuerdo;
        $acuerdo->id_usuario = Auth::user()->id;
        $acuerdo->activo = 'S';
        if($acuerdo->save()){
            return ['estado' => 'success', 'mensaje' => 'Acuerdo ingresado correctamente'];
        }else{
            return ['estado' => 'failed', 'mensaje' => 'Ha ocurrido un error al ingresar el Acuerdo, por favor intente nuevamente'];
        }
    }

    public function getAcuerdos(){
        return SecAcuerdos::all();
    }

    // public function getAcuerdoPorId($id){
    //     return SecAcuerdos::select('titulo', 'fecha', 'contenido', 'cliente_id', 'usuario_id', 'tipo_id')
    //         -join('sec_estado_acuerdos as ea', 'sec_acuerdos', '')
    //         ->where('tipo_id', $id)
    //         ->get();
    // }
}
