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

    protected $directiva = 1;

    //VALIDADOR
    public function validarDatosGo($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'numero_documento' => 'required|unique:cs_gastos_operacionales,numero_documento',
                'descripcion' => 'required|min:0',
                'definicion' => 'required|min:0',
                'monto' => 'required',
                'archivo_documento' => 'required|file|mimes:pdf',
            ],
            [
                'fecha.required' => 'La fecha es necesaria',
                'numero_documento.required' => 'El numero de documento es necesario',
                'numero_documento.unique' => 'El numero de documento ya existe en tus registros',
                'descripcion.required' => 'La descripcion es necesaria',
                'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
                'monto.required' => 'El monto es necesario',
                'archivo_documento.required' => 'El archivo comprobante es necesario',
                'archivo_documento.file' => 'El comprobante debe de ser un archivo',
                'archivo_documento.mimes' => 'El comprobante debe de estar en formato PDF'
            ]
        );


        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    public function validarGoModificacion($request){
        switch ($request->nombreCampo) {
            case 'fecha':
                $validator = Validator::make(
                    $request->all(),
                    [
                        'valor' => 'required'
                    ],
                    [
                        'valor.required' => 'Debes ingresar la fecha.'
                    ]
                );
                break;
            case 'numero_documento':
                $validator = Validator::make(
                    $request->all(),
                    [
                        'valor' => 'required|unique:cs_gastos_operacionales,numero_documento'
                    ],
                    [
                        'valor.required' => 'Debes ingresar un n° de documento.',
                        'valor.unique' => 'El numero de documento ya existe en tus registros.'
                    ]
                );
                break;

            case 'archivo_documento':
                $validator = Validator::make(
                    $request->all(),
                    [
                        'valor' => 'required|file|mimes:pdf'
                    ],
                    [
                        'valor.required' => 'Debes seleccionar un archivo.',
                        'valor.file' => 'Lo seleccionado debe ser un archivo.',
                        'valor.mimes' => 'El archivo debe ser extension PDF.'
                    ]
                );
                break;

            case 'descripcion':
                $validator = Validator::make(
                    $request->all(),
                    [
                        'valor' => 'required'
                    ],
                    [
                        'valor.required' => 'Debes ingresar una descripcion.'
                    ]
                );
                break;

            case 'monto':
                $validator = Validator::make(
                    $request->all(),
                    [
                        'valor' => 'required|integer|min:1'
                    ],
                    [
                        'valor.required' => 'Debes ingresar un monto.',
                        'valor.min' => 'El monto ingresado debe ser mayor a 0.',
                        'valor.integer' => 'Debes ingresar solo numeros.'
                    ]
                );
                break;

            default:
                # code...
                break;
        }

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
            $consultaDetalles = $this->totalesGO($consulta['totales']);
            /* dd($consulta); */
            if ($consulta['estado'] == 'success') {
                if($consultaDetalles['estado'] == 'success'){
                    if($value->monto > $consultaDetalles['totales'][0]->cierre_mes){
                       return ['estado' => 'failed', 'mensaje' => 'El monto solicitado es mayor a lo disponible'];
                    }
                }else{
                    if($value->monto > $consulta['totales']->monto_egreso){
                        return ['estado' => 'failed', 'mensaje' => 'El monto solicitado es mayor a lo disponible'];
                    }
                }
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

    public function directiva()
	{
		$data = DB::table('directiva')->select('id', 'directiva')->where('activo','S')->first();
		return $data;
	}

    public function validar_monto_inicio()
    {
        $sumaDetalle = Cs_gastos_operacionales_detalle::where('directiva',$this->directiva()->id)->sum('monto');
        /* dd($sumaDetalle); */
        if ($sumaDetalle) {
            return ['estado' => 'success', 'totales' => $sumaDetalle];
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
    protected function totalesGO($mi)
    {
        $totales = DB::table('cs_gastos_operacionales')
            ->select([
                DB::raw('sum(coalesce(monto_ingreso, 0)) as totalIngreso'),
                DB::raw('sum(coalesce(monto_egreso, 0)) as totalEgreso'),
                DB::raw('sum(coalesce(monto_ingreso, 0)) - sum(coalesce(monto_egreso, 0)) as total')
            ])
            ->where([
                /* 'anio_id' => $anio,
                'mes_id' => $mes, */
                'activo' => 'S'
            ])
            ->get();

        if (!$totales->isEmpty()) {
            //dd($mi); 1
            $totales[0]->cierre_mes = $totales[0]->total + $mi;
            return ['estado' => 'success', 'totales' => $totales];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aún no hay datos ingresados en la fecha ingresada.'];
        }
    }

    protected function listar($anio, $mes)
    {

        $directiva = $this->directiva();
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
            /* ->where([
                'anio_id' => $anio,
                'mes_id' => $mes
            ]) */
            ->orderby('a.descripcion','asc')
            ->orderby('mes_id', 'asc')
            ->orderby('dia', 'asc')
            ->get();

        if (!$cs->isEmpty()) {
            $tomar = true;
            /* $gomi = Cuentasindicato::select([
                'monto_egreso'
            ])
                ->where([
                    'anio_id' => $anio,
                    'mes_id' => $mes,
                    'tipo_cuenta_sindicato' => 6
                ])
                ->get(); */
            $gomi = $this->validar_monto_inicio();
            /* dd($gomi); */
            $totales = $this->totalesGO($gomi['totales']);

            for ($i = 0; $i < count($cs); $i++) {
                switch ($cs[$i]->definicion) {
                    case 1:
                        if ($tomar == true) {
                            $cs[$i]->saldo_actual = $gomi['totales'] + $cs[$i]->monto_ingreso;
                            $tomar = false;
                        } else {
                            $cs[$i]->saldo_actual = $cs[$i - 1]->saldo_actual + $cs[$i]->monto_ingreso;
                        }
                        break;
                    case 2:
                        if ($tomar == true) {
                            $cs[$i]->saldo_actual = $gomi['totales'] - $cs[$i]->monto_egreso;
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

            return ['estado' => 'success', 'gastosOperacionales' => $cs, 'montoInicial' => $gomi['totales'], 'totales' => $totales['totales'][0], 'directiva' => $directiva];
        }
        return ['estado' => 'failed', 'mensaje' => 'No hay gastos operacionales en el mes o año seleccionado'];
    }

    protected function modificarGastoOperacional($request)
    {
        /* dd($request->all()); */
        $validarDatos = $this->validarGoModificacion($request);
        if ($validarDatos['estado'] == 'success') { 
            $modificar = Cs_gastos_operacionales::find($request->input);

            if (!is_null($modificar)) {
                switch ($request->nombreCampo) {
                    case 'fecha':
                        $f = $this->div_fecha($request->valor);
                        $a = $this->anio_tipo_id($f['anio']);

                        $modificar->dia = $f['dia'];
                        $modificar->mes_id = $f['mes'];
                        $modificar->anio_id = $a->id;
                        //dd($modificar);
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Fecha actualizada correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'numero_documento':
                        $modificar->numero_documento = $request->valor;
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'N° de documento actualizado correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'archivo_documento':
                        $ruta = substr($modificar->archivo_documento, 8);
                        $borrar = Storage::delete($ruta);
                        if ($borrar) {
                            $guardarArchivo = $this->guardarArchivo($request->valor, 'ArchivosGastosOperacionales/');
                            if ($guardarArchivo['estado'] == 'success') {
                                $modificar->archivo_documento = $guardarArchivo['archivo'];
                                if ($modificar->save()) {
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
                        break;

                    case 'descripcion':
                        $modificar->descripcion = $request->valor;
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Descripcion actualizada correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    case 'monto':
                        $modificar->monto_egreso = $request->valor;
                        if ($modificar->save()) {
                            return ['estado' => 'success', 'mensaje' => 'Monto actualizado correctamente.'];
                        } else {
                            return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El Item que intentas modificar no existe.'];
            }
         } else {
            return $validarDatos;
        }
    }

    protected function actualizarSaldoDisponible($request)
    {
        //OBTENER EL SALDO DISPONIBLE DE GASTO OPERACIONAL
        $montoBaseDetalle = $this->validar_monto_inicio();
        $consultaDetalles = $this->totalesGO($request->idAnio, $request->idMes, $montoBaseDetalle['totales']);
        $saldoDisponible = $consultaDetalles['totales'][0]->cierre_mes;
        /* dd($request->valor + $saldoDisponible); */

        //OBTENER GASTO OPERACIONAL DE CUENTA SINDICAL Y ACTUALIZAR
        $consulta = Cuentasindicato::where(
            [
             'anio_id'=> $request->idAnio,
             'mes_id'=> $request->idMes,
             'tipo_cuenta_sindicato' => 6
            ])->first();
            //si existe item go en el mes y año de los inputs enmtrantes, entrar a if
            if($consulta){
                //$consulta->monto_egreso = ($request->valor + $saldoDisponible);
                $consulta->monto_egreso += ($request->valor);
                if($consulta->save()){
                    //OBTENER GASTO OPERACIONAL DETALLE Y GUARDAR
                    $god = new Cs_gastos_operacionales_detalle;
                    $god->cs_cuenta_sindicato_id = $consulta->id;
                    $god->mes_id = $request->idMes;
                    $god->anio_id = $request->idAnio;
                    $god->descripcion = $request->descripcion;
                    $god->activo = 'S';
                    $god->monto = $request->valor;
                    $god->directiva = $this->directiva()->id;//directiva 1 de tio emilio
                    if($god->save()){
                         return ['estado' => 'success', 'mensaje' => 'Monto actualizado correctamente.'];
                    }
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'A ocurrido un error, intenta nuevamente.'];
                }
            }else{
                $cs = new Cuentasindicato;
                $cs->numero_documento = '--';
                $cs->archivo_documento = '--';
                $cs->tipo_cuenta_sindicato = 6;// tipo gasto operacional
                $cs->descripcion = $request->descripcion;
                $cs->monto_egreso = $request->valor;
                $cs->definicion=2;
                $cs->user_crea = Auth::user()->id; 
                $cs->dia = 1; // por defecto el primer dia
                $cs->mes_id = $request->idMes;
                $cs->anio_id = $request->idAnio;
                $cs->activo='S';
                if($cs->save()){
                      //OBTENER GASTO OPERACIONAL DETALLE Y GUARDAR
                      $god = new Cs_gastos_operacionales_detalle;
                      $god->cs_cuenta_sindicato_id = $cs->id;
                      $god->mes_id = $request->idMes;
                      $god->anio_id = $request->idAnio;
                      $god->descripcion = $request->descripcion;
                      $god->activo = 'S';
                      $god->monto = $request->valor;
                      $god->directiva = $this->directiva()->id;//directiva 1 de tio emilio
                     
                      if($god->save()){
                        return ['estado' => 'success', 'mensaje' => 'Monto actualizado correctamente.'];
                      }
                }
            }
            
    }
}
