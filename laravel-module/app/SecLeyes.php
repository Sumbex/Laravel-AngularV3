<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SecLeyes extends Model
{
    protected $table = "sec_leyes";

    protected function ingresarNoticia($request)
    {
        /* dd($request->all()); */
        $noticia = new SecLeyes;
        $noticia->titulo = $request->titulo;
        $noticia->slug =  Str::slug($request->titulo, '-');
        $noticia->cuerpo = $request->cuerpo;
        $guardarArchivo = $this->guardarArchivo($request->archivo, 'ArchivosNoticias/');
        if ($guardarArchivo['estado'] == "success") {
            $noticia->archivo = $guardarArchivo['archivo'];
        } else {
            return $guardarArchivo;
        }
        $noticia->sec_leyes_estado = 1;
        $noticia->activo = 'S';
        if ($noticia->save()) {
            return ['estado' => 'success', 'mensaje' => 'Noticia creada correctamente.'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
        }
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
            return ['estado' =>  'failed', 'mensaje' => 'Error al guardar el archivo.'];
        }
    }
}
