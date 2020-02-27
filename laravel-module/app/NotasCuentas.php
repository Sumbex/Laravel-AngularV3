<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotasCuentas extends Model
{
    protected $table = "notas";

    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cuenta_id' => 'required',
                'fecha' => 'required',
                'numero_documento' => 'required|unique:notas,numero_documento',
                'archivo' => 'required|file|mimes:pdf',
                'descripcion' => 'required',
            ],
            [
                'cuenta_id.required' => 'Debes seleccionar una cuenta.',
                'fecha.required' => 'Debes ingresar la fecha.',
                'numero_documento.required' => 'Debes ingresar un n° de documento.',
                'numero_documento.unique' => 'El numero de documento ya existe en tus registros.',
                'archivo.required' => 'Debes seleccionar un archivo.',
                'archivo.file' => 'Lo seleccionado debe ser un archivo.',
                'archivo.mimes' => 'El archivo debe ser extension PDF.',
                'descripcion.required' => 'Debes ingresar una descripcion.',
            ]
        );

        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function fecha($value)
    {
        $fecha = $value;
        $anio = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $anio, 'mes' => $mes, 'dia' => $dia
        ];
    }
    protected function anioID($value)
    {
        return DB::table('anio')->where('descripcion', $value)->first()->id;
    }

    protected function mesID($value)
    {
        return DB::table('mes')->where('id', $value)->first()->id;
    }

    protected function guardarArchivo($archivo, $ruta)
    {
        $filenameext = $archivo->getClientOriginalName();
        $filename = pathinfo($filenameext, PATHINFO_FILENAME);
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = $filename . '_' . time() . '.' . $extension;
        $rutaDB = 'storage/' . $ruta . $nombreArchivo;

        $guardar = Storage::put($ruta . $nombreArchivo, (string) file_get_contents($archivo), 'public');

        if ($guardar) {
            return ['estado' =>  'success', 'archivo' => $rutaDB];
        } else {
            return ['estado' =>  'failed', 'mensaje' => 'Error al intentar guardar el archivo.'];
        }
    }


    protected function ingresarNota($request)
    {
        /* dd($request->all()); */
        $validarDatos = $this->validarDatos($request);
        if ($validarDatos['estado'] == 'success') {
            $fecha = $this->fecha($request->fecha);
            $anio = $this->anioID($fecha['anio']);
            $mes = $this->mesID($fecha['mes']);
            $nota = new NotasCuentas;
            $nota->cuenta_id = $request->cuenta_id;
            $nota->anio_id = $anio;
            $nota->mes_id = $mes;
            $nota->dia = $fecha['dia'];
            $nota->numero_documento = $request->numero_documento;
            $guardarArchivo = $this->guardarArchivo($request->archivo, 'ArchivosNotasCuentas/');
            if ($guardarArchivo['estado'] == "success") {
                $nota->archivo = $guardarArchivo['archivo'];
            } else {
                return $guardarArchivo;
            }
            $nota->descripcion = $request->descripcion;
            $nota->user_crea = Auth::user()->id;
            $nota->activo = "S";
            if ($nota->save()) {
                return ['estado' => 'success', 'mensaje' => 'Nota ingresada correctamente.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
            }
        } else {
            return $validarDatos;
        }
    }

    protected function traerNotas($anio, $mes, $cuenta)
    {
        $notas = DB::table('notas as n')
            ->select([
                'n.id',
                'n.cuenta_id',
                DB::raw("
                CASE
                    WHEN (cuenta_id  = 1) THEN 'Cuenta Sindical'
                    WHEN (cuenta_id  = 2) THEN 'Cuenta Bienestar'
                    WHEN (cuenta_id  = 3) THEN 'Cuenta Consorcio'
                END AS cuenta"),
                DB::raw("concat(n.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha"),
                'n.numero_documento',
                'n.archivo',
                DB::raw("upper(n.descripcion) as descripcion"),
            ])
            ->join('anio as a', 'a.id', 'n.anio_id')
            ->join('mes as m', 'm.id', 'n.mes_id')
            ->where([
                'n.cuenta_id' => $cuenta,
                'n.anio_id' => $anio,
                'n.mes_id' => $mes,
                'n.activo' => 'S'
            ])
            ->get();
        if (!$notas->isEmpty()) {
            return ['estado' => 'success', 'notas' => $notas];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay notas en la cuenta o fecha seleccionada.'];
        }
    }

    protected function traerCuentas()
    {
        return $cuentas[] = [['id' => 1, 'descripcion' => 'Cuenta Sindical'], ['id' => 2, 'descripcion' => 'Cuenta Bienestar'], ['id' => 3, 'descripcion' => 'Cuenta Consorcio']];
    }
}
