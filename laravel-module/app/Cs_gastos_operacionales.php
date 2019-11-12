<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Cuentasindicato;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Cs_gastos_operacionales extends Model
{
    protected $table = 'cs_gastos_operacionales';

    //VALIDADOR
    public function validarDatosGo($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'numero_documento' => 'required|unique:cuenta_sindicato,numero_documento',
                'descripcion' => 'required|min:0',
                'definicion' => 'required|min:0',
                'monto' => 'required',
                'archivo_documento' => 'required|file|mimes:pdf',
            ],
            [
                'fecha.required' => 'La fecha es necesaria',
                'n_documento.required' => 'El numero de documento es necesario',
                'n_documento.unique' => 'El numero de documento ya existe en tus registros',
                'descripcion.required' => 'La descripcion es necesaria',
                'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
                'monto.required' => 'El monto es necesario'
            ]
        );


        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    protected function guardar($value)
    {
        $validador = $this->validarDatosGo($value);
        if ($validador['estado'] == 'success') {
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
                } else {
                    return $guardarArchivo;
                }
            }
            return ['estado' => 'failed', 'mensaje' => 'Aún no hay inicio de caja en el mes seleccionado'];
        }else{
            return $validador;
        }
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

    //Funcion para obtener los totales
    protected function totalesGO($anio, $mes)
    {
        $totales = DB::table('cs_gastos_operacionales')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as totalIngreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as totalEgreso'),
                DB::raw('sum(coalesce(monto_ingreso, 0)) - sum(coalesce(monto_egreso, 0)) as total')
            ])
            ->where([
                'anio_id' => $anio,
                'mes_id' => $mes,
                'activo' => 'S'
            ])
            ->get();

        if (!$totales->isEmpty()) {
            return ['estado' => 'success', 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function listar($anio, $mes)
    {

        $cs = Cs_gastos_operacionales::select([
            DB::raw("concat(dia,' de ',m.descripcion,' del ',a.descripcion) as fecha"),
            'cs_gastos_operacionales.id',
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
            ])
            ->orderby('dia', 'asc')
            ->get();

        if (!$cs->isEmpty()) {
            $tomar = true;
            $gomi = Cuentasindicato::select([
                'monto_egreso'
            ])
                ->where([
                    'anio_id' => $anio,
                    'mes_id' => $mes,
                    'tipo_cuenta_sindicato' => 6
                ])
                ->get();

            $totales = $this->totalesGO($anio, $mes);

            for ($i = 0; $i < count($cs); $i++) {
                switch ($cs[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $cs[$i]->saldo_actual = $gomi[0]->monto_egreso + $cs[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $cs[$i]->saldo_actual = $cs[$i - 1]->saldo_actual + $cs[$i]->monto_ingreso;
                        }
                        break;
                    case 2:
                        if ($tomar == true) {
                            $cs[$i]->saldo_actual = $gomi[0]->monto_egreso - $cs[$i]->monto_egreso;
                            $tomar = false;
                        } else {
                            $cs[$i]->saldo_actual = $cs[$i - 1]->saldo_actual - $cs[$i]->monto_egreso;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }

            return ['estado' => 'success', 'gastosOperacionales' => $cs, 'montoInicial' => $gomi[0]->monto_egreso, 'totales' => $totales['totales'][0]];
        }
        return ['estado' => 'failed', 'mensaje' => 'No hay gastos operacionales en el mes o año seleccionado'];
    }
}
