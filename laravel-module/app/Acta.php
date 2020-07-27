<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    // protected $table = 'sec_actas';
     protected $table = 'sec_desarrollo_asamblea';

    protected function insertar($r)
    {

        if ($r->fecha == '' || $r->hora == '' || $r->ubicacion == '') {
            return [
                'estado'=>'failed', 'mensaje'=>'Faltan datos en el encabezado'
            ];
        }

        if ($r->tipo == 'ordinaria') {
            $a = $this;
            $a->socio_crea = Auth::user()->id;
            $a->encabezado = "En ".$r->ubicacion." a ".$r->fecha." siendo las ".$r->hora." hrs. Se da inicio al desarrollo de asamblea de caracter ".$r->tipo.", en la cual se dicta lo siguiente:";
            $a->tabla = $r->tabla;
            $a->resumen_anterior = $r->resumen_anterior;
            $a->rendicion_cuentas = $r->rendicion_cuentas;
            $a->rendicion_camping = $r->rendicion_camping;
            $a->rendicion_bienestar = $r->rendicion_bienestar;
            $a->rendicion_directorio = $r->rendicion_directorio;
            $a->ayuda_aporte = $r->ayuda_aporte;
            $a->socios_nuevos = $r->socios_nuevos;
            $a->varios = $r->varios;
            $a->total_socios = $r->total_socios;
            $a->total_asistidos = $r->total_asistidos;
            $a->total_justificados = $r->total_justificados;
            $a->sin_justificar = $r->sin_justificar;
            $a->tipo = $r->tipo;
            $a->fecha = $r->fecha;
            $a->hora = $r->hora;
            $a->ubicacion = $r->ubicacion;
            $a->activo = 'S';
            $a->estado = 1;
            if ($a->save()) {
                return [
                    'estado' =>'success',
                    'mensaje' => 'Formulario registrado con exito!'
                ];
            }else{
                return [
                    'estado' =>'failed',
                    'mensaje' => 'Error al registrar'
                ];
            }

        }

        if ($r->tipo == 'extraordinaria') {
            $a = $this;
            $a->socio_crea = Auth::user()->id;
            $a->encabezado = "En ".$r->ubicacion." a ".$r->fecha." siendo las ".$r->hora." hrs. Se da inicio al desarrollo de asamblea de caracter ".$r->tipo.", en la cual se dicta lo siguiente:";
            $a->tabla = $r->tabla;
            $a->puntos_a_tratar = $r->puntos_a_tratar;
            
            $a->total_socios = $r->total_socios;
            $a->total_asistidos = $r->total_asistidos;
            $a->total_justificados = $r->total_justificados;
            $a->sin_justificar = $r->sin_justificar;
            $a->tipo = $r->tipo;
            $a->fecha = $r->fecha;
            $a->hora = $r->hora;
            $a->ubicacion = $r->ubicacion;
            $a->activo = 'S';
            $a->estado = 1;
            if ($a->save()) {
                return [
                    'estado' =>'success',
                    'mensaje' => 'Formulario registrado con exito!'
                ];
            }else{
                return [
                    'estado' =>'failed',
                    'mensaje' => 'Error al registrar'
                ];
            }

        }
        // $a = $this;
        // $a->socio_crea = Auth::user()->id;
        // $a->encabezado = "En ".$r->ubicacion." a ".$r->fecha." siendo las ".$r->hora.". Se da inicio a la asamblea general de socios siendo dirigida por el ".$r->dirigente.", se hace lectura al Acta anterior la que es aprobada sin observaciones.";
        // $a->miembros = $r->miembros;
        // $a->orden_dia = $r->orden_dia;
        // $a->detalle_orden_dia = $r->detalle_orden_dia;
        // $a->fecha = $r->fecha;
        // $a->hora = $r->hora;
        // $a->ubicacion = $r->ubicacion;
        // $a->dirigente = $r->dirigente;
        // $a->activo = 'S';
        // $a->estado = 1;
        // if ($a->save()) {
        //     return [
        //         'estado' =>'success',
        //         'mensaje' => 'Acta registrada con exito!'
        //     ];
        // }else{
        //     return [
        //         'estado' =>'failed',
        //         'mensaje' => 'Error al registrar'
        //     ];
        // }
    }

    protected function actualizar($r)
    {
        if ($r->fecha == '' || $r->hora == '' || $r->ubicacion == '') {
            return [
                'estado'=>'failed', 'mensaje'=>'Faltan datos en el encabezado'
            ];
        }

        if ($r->tipo == 'ordinaria') {
            // dd(($r->rendicion_camping!="null")?$r->rendicion_camping:'');
            $a = $this::where(['activo'=>'S', 'id'=>$r->desarrollo_asamblea_id])->first();
            $a->socio_crea = Auth::user()->id;
            $a->encabezado = "En ".$r->ubicacion." a ".$r->fecha." siendo las ".$r->hora." hrs. Se da inicio al desarrollo de asamblea de caracter ".$r->tipo.", en la cual se dicta lo siguiente:";
            $a->tabla = ($r->tabla!="null")?$r->tabla:null;
            $a->resumen_anterior = ($r->resumen_anterior!="null")?$r->resumen_anterior:null;
            $a->rendicion_cuentas =($r->rendicion_cuentas!="null")?$r->rendicion_cuentas:null;
            $a->rendicion_camping =($r->rendicion_camping!="null")?$r->rendicion_camping:null;
            $a->rendicion_bienestar =($r->rendicion_bienestar!="null")?$r->rendicion_bienestar:null;
            $a->rendicion_directorio =($r->rendicion_directorio!="null")?$r->rendicion_directorio:null;
            $a->ayuda_aporte = ($r->ayuda_aporte!="null")?$r->ayuda_aporte:null;
            $a->socios_nuevos =($r->socios_nuevos!="null")?$r->socios_nuevos:null;
            $a->varios = ($r->varios!="null")?$r->varios:null;
            $a->total_socios = ($r->total_socios!="null")?$r->total_socios:null;
            $a->total_asistidos = ($r->total_asistidos!="null")?$r->total_asistidos:null;
            $a->total_justificados = ($r->total_justificados!="null")?$r->total_justificados:null;
            $a->sin_justificar = ($r->sin_justificar!="null")?$r->sin_justificar:null;
            $a->tipo = $r->tipo;
            $a->fecha = $r->fecha;
            $a->hora = $r->hora;
            $a->ubicacion = $r->ubicacion;
            // $a->activo = 'S';
            // $a->estado = 1;
            if ($a->save()) {
                return [
                    'estado' =>'success',
                    'mensaje' => 'Formulario actualizado con exito!'
                ];
            }else{
                return [
                    'estado' =>'failed',
                    'mensaje' => 'Error al registrar'
                ];
            }

        }
    }

    protected function actualizar2($r)//extraordinria
    {
        if ($r->fecha == '' || $r->hora == '' || $r->ubicacion == '') {
            return [
                'estado'=>'failed', 'mensaje'=>'Faltan datos en el encabezado'
            ];
        }
        if ($r->tipo == 'extraordinaria') {
            $a = $this::where(['activo'=>'S', 'id'=>$r->desarrollo_asamblea_id])->first();
            $a->socio_crea = Auth::user()->id;
            $a->encabezado = "En ".$r->ubicacion." a ".$r->fecha." siendo las ".$r->hora." hrs. Se da inicio al desarrollo de asamblea de caracter ".$r->tipo.", en la cual se dicta lo siguiente:";
            $a->tabla = ($r->tabla!="null")?$r->tabla:null;
            $a->puntos_a_tratar = ($r->puntos_a_tratar!="null")?$r->puntos_a_tratar:null;
            
            $a->total_socios = ($r->total_socios!="null")?$r->total_socios:null;
            $a->total_asistidos = ($r->total_asistidos!="null")?$r->total_asistidos:null;
            $a->total_justificados = ($r->total_justificados!="null")?$r->total_justificados:null;
            $a->sin_justificar = ($r->sin_justificar!="null")?$r->sin_justificar:null;

            $a->tipo = $r->tipo;
            $a->fecha = $r->fecha;
            $a->hora = $r->hora;
            $a->ubicacion = $r->ubicacion;
            // $a->activo = 'S';
            // $a->estado = 1;
            if ($a->save()) {
                return [
                    'estado' =>'success',
                    'mensaje' => 'Formulario actualizado con exito!'
                ];
            }else{
                return [
                    'estado' =>'failed',
                    'mensaje' => 'Error al registrar'
                ];
            }

        }
        
    }

    public function listar()
    {
        $listar = Acta::all();

        if ($listar) {
            return response()->json($listar);
        }
    }

    public function traer($id)
    {
        # code...
    }


}
