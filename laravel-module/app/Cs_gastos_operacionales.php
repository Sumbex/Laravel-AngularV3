<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Cuentasindicato;
use Illuminate\Support\Facades\Storage;

class Cs_gastos_operacionales extends Model
{
    protected $table = 'cs_gastos_operacionales';

    protected function guardar($value)
    {
        $guardarArchivo = $this->guardarArchivo($value->archivo_documento, 'ArchivosGastosOperacionales/');
        $f = $this->div_fecha($value->fecha);
        $a = $this->anio_tipo_id($f['anio']);
        $consulta = $this->validar_monto_inicio($f['mes'], $a->id);
        if ($consulta) {
            $go = $this;
            $go->dia = $f['dia'];
            $go->mes_id = $f['mes'];
            $go->anio_id = $a->id;
            $go->numero_documento = $value->numero_documento;
            $go->archivo_documento = $guardarArchivo['archivo'];
            if ($guardarArchivo['estado'] == 'success') {
                $go->tipo_cuenta_sindicato = 6;
                $go->descripcion = $value->descripcion;
                $go->definicion = $value->definicion;

                switch ($value->definicion) {
                    case '1':
                        $go->monto_ingreso = $value->monto;
                        break;

                    case '2':
                        $go->monto_egreso = $value->monto;
                        break;

                    default:
                        return ['estado' => 'failed', 'mensaje' => 'Se ha producido un error al recuperar el monto'];
                        break;
                }



                $go->user_crea = Auth::user()->id;
                $go->activo = 'S';

                if ($go->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Se ha ingresado correctamente'];
                }

                return ['estado' => 'failed', 'mensaje' => 'Se ha producido un error al ingresar'];
            }else{
                return $guardarArchivo;
            }
        }
        return ['estado' => 'failed', 'mensaje' => 'Aún no hay inicio de caja en el mes seleccionado'];
    }

    public function div_fecha($value) //funciona con input type date 
    {
        $fecha = $value;
        $ano = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $ano,
            'mes'  => $mes,
            'dia'  => $dia
        ];
    }

    public function anio_tipo_id($value)
    {

        $data = DB::table('anio')->where('descripcion', $value)->first();
        if (!empty($data)) {
            return $data;
        }
    }

    public function validar_monto_inicio($mes, $anio, $tipo = 6)
    {
        $cs = Cuentasindicato::where([
            'tipo_cuenta_sindicato' => $tipo,
            'anio_id' => $anio,
            'mes_id' => $mes
        ])->first();

        if ($cs) {
            return true;
        }
        return false;
    }

    //proceso para guadar el archivo
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

    protected function listar($anio, $mes)
    {
        $cs = Cs_gastos_operacionales::select([
            DB::raw("concat(dia,' de ',m.descripcion,' del ',a.descripcion) as fecha"),
            'numero_documento',
            'archivo_documento',
            'cs_gastos_operacionales.descripcion',
            'definicion',
            'monto_ingreso',
            'monto_egreso',
            'user_crea'
        ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->where([
                'anio_id' => $anio,
                'mes_id' => $mes
            ])->get();

        if (!$cs->isEmpty()) {
            return ['estado' => 'success', 'gastosOperacionales' => $cs];
        }
        return ['estado' => 'failed', 'mensaje' => 'No hay gastos operacionales en el mes o año seleccionado'];
    }
}
