<?php

namespace App\Http\Controllers;

use App\LiquidacionEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LiquidacionEmpleadoController extends Controller
{
    public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'rut_trabajador' => 'required|unique:liq_empleado,rut_trabajador',
                'nombre_trabajador' => 'required',
                'cargo' => 'required',
                'nombre_afp' => 'required',
                'nombre_isapre' => 'required',
                'rut_empresa' => 'required',
                'nombre_empresa' => 'required',
                'direccion_empresa' => 'required',
            ],
            [

                'rut_trabajador.required' => 'Debe ingresar un rut valido',
                'rut_trabajador.unique' => 'El rut ingresado ya existe en nuestros registros',
                'nombre_trabajador.required' => 'Debe ingresar',
                'cargo.required' => 'Debe ingresar un cargo',
                'nombre_afp.required' => 'Debe ingresar una AFP',
                'nombre_isapre.required' => 'Debe ingresar una Isapre',
                'rut_empresa.required' => 'Debe ingresar el rut de Empresa',
                'nombre_empresa.required' => 'Debe ingresar el Nombre de Empresa',
                'direccion_empresa.required' => 'Debe ingresar la direccion de Empresa',
            ]
        );


        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    public function crear_empleado(Request $request)
    {
        $empleado = new LiquidacionEmpleado;

        $rut_limpio = $this->limpiar($request->rut_trabajador);

        $validador = $this->validarDatos($request);
        $exist = LiquidacionEmpleado::where([ 'activo'=>'S','rut_trabajador'=> $rut_limpio ])->get();

        if (count($exist)>0) {
            return ['estado'=>'failed','mensaje'=>'Este rut ya existe en la base de datos'];
        }

        if ($validador['estado'] == 'success') {
            if (!$this->valida_rut($rut_limpio)) {
                return ['estado'=>'failed','mensaje'=>'Rut no valido'];
            }

            $empleado->rut_trabajador = strtolower($rut_limpio);
            $empleado->nombre_trabajador = strtolower($request->nombre_trabajador);
            $empleado->cargo = strtolower($request->cargo);
            $empleado->nombre_afp = strtolower($request->nombre_afp);
            $empleado->nombre_isapre = strtolower($request->nombre_isapre);
            $empleado->rut_empresa = strtolower($request->rut_empresa);
            $empleado->nombre_empresa = strtolower($request->nombre_empresa);
            $empleado->direccion_empresa = strtolower($request->direccion_empresa);
            $empleado->activo = 'S';
            if ($empleado->save()) {
                return ['estado' => 'success', 'mensaje' => 'Empleado agregado correctamente.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'Se ha producido un error al ingresar el nuevo empleado.'];
            }
        } else {
            return $validador;
        }
    }

    public function validarDatosActualizar($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre_trabajador' => 'required',
                'cargo' => 'required',
                'nombre_afp' => 'required',
                'nombre_isapre' => 'required',
                'rut_empresa' => 'required',
                'nombre_empresa' => 'required',
                'direccion_empresa' => 'required',
            ],
            [
                'nombre_trabajador.required' => 'Debe ingresar',
                'cargo.required' => 'Debe ingresar un cargo',
                'nombre_afp.required' => 'Debe ingresar una AFP',
                'nombre_isapre.required' => 'Debe ingresar una Isapre',
                'rut_empresa.required' => 'Debe ingresar el rut de Empresa',
                'nombre_empresa.required' => 'Debe ingresar el Nombre de Empresa',
                'direccion_empresa.required' => 'Debe ingresar la direccion de Empresa',
            ]
        );


        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    }

    public function actualizar_empleado(Request $request)
    {
        $empleado = LiquidacionEmpleado::find($request->id);
        $validador = $this->validarDatosActualizar($request);
        if ($validador['estado'] == 'success') {
            if (!is_null($empleado)) {
                $empleado->nombre_trabajador = strtolower($request->nombre_trabajador);
                $empleado->cargo = strtolower($request->cargo);
                $empleado->nombre_afp = strtolower($request->nombre_afp);
                $empleado->nombre_isapre = strtolower($request->nombre_isapre);
                $empleado->rut_empresa = strtolower($request->rut_empresa);
                $empleado->nombre_empresa = strtolower($request->nombre_empresa);
                $empleado->direccion_empresa = strtolower($request->direccion_empresa);
                $empleado->activo = 'S';

                if ($empleado->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Empleado actualizado correctamente.'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'Se ha producido un error al actualizar el empleado.'];
                }
            }
        } else {
            return $validador;
        }
    }

    public function traer_empleados()
    {
        $busqueda = LiquidacionEmpleado::select([
                'id',
                'rut_trabajador',
                'nombre_trabajador',
                'cargo',
                'nombre_afp',
                'nombre_isapre',
                'rut_empresa',
                'nombre_empresa',
                'direccion_empresa',
            ])
                ->where('activo', 'S')
                ->get();
        if (count($busqueda) > 0) {
            return ['estado' => 'success', 'empleado' => $busqueda];
        }
    }

    public function valida_rut($rut)
    {
        try {
            $rut = preg_replace('/[^k0-9]/i', '', $rut);
            $dv  = substr($rut, -1);
            $numero = substr($rut, 0, strlen($rut)-1);
            $i = 2;
            $suma = 0;
            foreach (array_reverse(str_split($numero)) as $v) {
                if ($i==8) {
                    $i = 2;
                }
                $suma += $v * $i;
                ++$i;
            }
            $dvr = 11 - ($suma % 11);
            
            if ($dvr == 11) {
                $dvr = 0;
            }
            if ($dvr == 10) {
                $dvr = 'K';
            }
            if ($dvr == strtoupper($dv)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function limpiar($s)
    {
        $s = str_replace('á', 'a', $s);
        $s = str_replace('Á', 'A', $s);
        $s = str_replace('é', 'e', $s);
        $s = str_replace('É', 'E', $s);
        $s = str_replace('í', 'i', $s);
        $s = str_replace('Í', 'I', $s);
        $s = str_replace('ó', 'o', $s);
        $s = str_replace('Ó', 'O', $s);
        $s = str_replace('Ú', 'U', $s);
        $s= str_replace('ú', 'u', $s);
        $s= str_replace('K', 'k', $s);

        //Quitando Caracteres Especiales
        $s= str_replace('"', '', $s);
        $s= str_replace(':', '', $s);
        $s= str_replace('.', '', $s);
        $s= str_replace(',', '', $s);
        $s= str_replace(';', '', $s);
        $s= str_replace('-', '', $s);
        return $s;
    }
}
