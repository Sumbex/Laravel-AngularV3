<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Archivador extends Model
{
    protected $table = "cs_archivador";

    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'titulo' => 'required',
                'tipo' => 'required',
                'archivo' => 'required|file',/* |mimes:xlsx */
            ],
            [
                'fecha.required' => 'Debes ingresar la fecha.',
                'titulo.required' => 'Debes ingresar una descripcion.',
                'tipo.required' => 'Debes seleccionar un tipo de archivo.',
                'archivo.required' => 'Debes seleccionar un archivo.',
                'archivo.file' => 'Lo seleccionado debe ser un archivo.',
                /* 'archivo.mimes' => 'El archivo debe ser Excel.', */
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

    protected function validarExcel($archivo)
    {
        $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw");
        $result = array($archivo->getClientOriginalExtension());
        if (in_array($result[0], $extensions)) {
            return ['estado' => true];
        } else {
            return ['estado' => false, 'mensaje' => 'El archivo seleccionado debe ser excel.'];
        }
    }

    protected function validarPDF($archivo)
    {
        $extensions = array("pdf", "application/pdf");
        $result = array($archivo->getClientOriginalExtension());
        if (in_array($result[0], $extensions)) {
            return ['estado' => true];
        } else {
            return ['estado' => false, 'mensaje' => 'El archivo seleccionado debe ser PDF.'];
        }
    }

    protected function validarAct($archivo)
    {
        $extensions = array("pdf", "application/pdf", "xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw");
        $result = array($archivo->getClientOriginalExtension());
        if (in_array($result[0], $extensions)) {
            return ['estado' => true];
        } else {
            return ['estado' => false, 'mensaje' => 'El archivo seleccionado debe ser PDF o Excel.'];
        }
    }

    protected function ingresarArchivo($request)
    {
        $validarDatos = $this->validarDatos($request);
        if ($validarDatos['estado'] == 'success') {
            if ($request->tipo == 4) {
                $validarPDF = $this->validarPDF($request->archivo);
                if ($validarPDF['estado'] == true) {
                    return $this->setArchivo($request);
                } else {
                    return $validarPDF;
                }
            } else {
                $excel = $this->validarExcel($request->archivo);
                if ($excel['estado'] == true) {
                    return $this->setArchivo($request);
                } else {
                    return $excel;
                }
            }
        } else {
            return $validarDatos;
        }
    }

    protected function setArchivo($request)
    {
        $fecha = $this->fecha($request->fecha);
        $anio = $this->anioID($fecha['anio']);
        $mes = $this->mesID($fecha['mes']);
        $archivo = new Archivador;
        $archivo->anio_id = $anio;
        $archivo->mes_id = $mes;
        $archivo->dia = $fecha['dia'];
        $archivo->titulo = $request->titulo;
        $archivo->tipo_archivo_id = $request->tipo;
        $guardarArchivo = $this->guardarArchivo($request->archivo, 'ArchivosArchivador/');
        if ($guardarArchivo['estado'] == "success") {
            $archivo->archivo = $guardarArchivo['archivo'];
        } else {
            return $guardarArchivo;
        }
        $archivo->user_crea = Auth::user()->id;
        $archivo->activo = "S";
        if ($archivo->save()) {
            return ['estado' => 'success', 'mensaje' => 'Archivo ingresado correctamente.'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
        }
    }

    protected function actualizarArchivo($request)
    {
        /* dd($request->all()); */
        switch ($request->campo) {
            case 'fecha':
                $archivo = Archivador::find($request->id);
                if (!is_null($archivo)) {
                    $fecha = $this->fecha($request->input);
                    $anio = $this->anioID($fecha['anio']);
                    $mes = $this->mesID($fecha['mes']);
                    $archivo->anio_id = $anio;
                    $archivo->mes_id = $mes;
                    $archivo->dia = $fecha['dia'];
                    if ($archivo->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Fecha actualizada correctamente.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Archivo no encontrado.'];
                }
                break;

            case 'titulo':
                $archivo = Archivador::find($request->id);
                if (!is_null($archivo)) {
                    $archivo->titulo = $request->input;
                    if ($archivo->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Titulo actualizado correctamente.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Archivo no encontrado.'];
                }
                break;

            case 'tipo':
                $archivo = Archivador::find($request->id);
                if (!is_null($archivo)) {
                    $archivo->tipo_archivo_id = $request->input;
                    if ($archivo->save()) {
                        return ['estado' => 'success', 'mensaje' => 'Tipo actualizado correctamente.'];
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                    }
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Archivo no encontrado.'];
                }
                break;

            case 'archivo':
                $validarArchivo = $this->validarAct($request->input);
                if ($validarArchivo['estado'] == true) {
                    $archivo = Archivador::find($request->id);
                    if (!is_null($archivo)) {
                        $ruta = substr($archivo->archivo, 8);
                        $borrar = Storage::delete($ruta);
                        if ($borrar) {
                            $guardarArchivo = $this->guardarArchivo($request->input, 'ArchivosArchivador/');
                            if ($guardarArchivo['estado'] == "success") {
                                $archivo->archivo = $guardarArchivo['archivo'];
                                if ($archivo->save()) {
                                    return ['estado' => 'success', 'mensaje' => 'Archivo actualizado correctamente.'];
                                } else {
                                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                                }
                            } else {
                                return $guardarArchivo;
                            }
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                    } else {
                        return ['estado' => 'failed', 'mensaje' => 'Archivo no encontrado.'];
                    }
                } else {
                    return $validarArchivo;
                }
                break;

            default:
                # code...
                break;
        }
    }

    protected function traerArchivos($anio, $mes)
    {
        $archivos = DB::table('cs_archivador as ca')
            ->select([
                'ca.id',
                DB::raw("concat(ca.dia,' de ',m.descripcion,' del ',a.descripcion) as fecha"),
                DB::raw("upper(ca.titulo) as titulo"),
                'ca.tipo_archivo_id as tipo_id',
                'cta.tipo',
                'ca.archivo',
                DB::raw("upper(concat(u.nombres,' ',u.a_paterno,' ',u.a_materno)) as nombre")
            ])
            ->join('anio as a', 'a.id', 'ca.anio_id')
            ->join('mes as m', 'm.id', 'ca.mes_id')
            ->join('users as u', 'u.id', 'ca.user_crea')
            ->join('cs_tipo_archivo as cta', 'cta.id', 'ca.tipo_archivo_id')
            ->where([
                'ca.anio_id' => $anio,
                'ca.mes_id' => $mes,
                'ca.activo' => 'S'
            ])
            ->orderby('ca.dia', 'ASC')
            ->get();
        if (!$archivos->isEmpty()) {
            $return = [];
            $return['reporte'] = [];
            $return['descuento'] = [];
            $return['conciliacion'] = [];
            $return['otros'] = [];
            foreach ($archivos as $key) {
                switch ($key->tipo_id) {
                    case 1:
                        $return['reporte'][] = $key;
                        break;

                    case 2:
                        $return['descuento'][] = $key;
                        break;

                    case 3:
                        $return['conciliacion'][] = $key;
                        break;

                    case 4:
                        $return['otros'][] = $key;
                        break;

                    default:
                        # code...
                        break;
                }
            }
            return ['estado' => 'success', 'archivos' => $return];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay archivos en la fecha seleccionada.'];
        }
    }

    protected function traerTipos()
    {
        $tipos = DB::table('cs_tipo_archivo')
            ->select([
                'id',
                'tipo'
            ])
            ->where([
                'activo' => 'S'
            ])
            ->get();
        if (!$tipos->isEmpty()) {
            return ['estado' => 'success', 'tipos' => $tipos];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'No hay tipos creados.'];
        }
    }
}
