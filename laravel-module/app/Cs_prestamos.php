<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Cs_prestamos extends Model
{
    protected $table = 'cs_prestamos';

    /* public function validarDatos($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'fecha' => 'required',
                'archivo_documento' => 'required|file|mimes:pdf',
                'numero_documento' => 'required|unique:cs_caja_chica,numero_documento',
                'descripcion' => 'required|min:0',
                'definicion' => 'required|min:0',
                'monto' => 'required|integer|min:1|max:100000',

            ],
            [
                'fecha.required' => 'La fecha es necesaria',
                'archivo_documento.required' => 'Debe seleccionar un archivo',
                'archivo_documento.file' => 'Lo seleccionado debe ser un archivo',
                'archivo_documento.mimes' => 'El archivo debe ser extension PDF',
                'numero_documento.required' => 'El numero de documento es necesario',
                'numero_documento.unique' => 'El numero de documento ya existe en tus registros',
                'descripcion.required' => 'La descripcion es necesaria',
                'definicion.required' => 'Especifique si su detalle es ingreso o egreso',
                'monto.required' => 'El monto es necesario',
                'monto.integer' => 'Debe ingresar solo numeros',
                'monto.min' => 'El monto debe ser mayor a 0.',
                'monto.max' => 'El monto no debe ser mayor a 100000 pesos.'
            ]
        );

        if ($validator->fails()) {
            return ['estado' => 'failed_v', 'mensaje' => $validator->errors()];
        }
        return ['estado' => 'success', 'mensaje' => 'success'];
    } */

    protected function ingresarPrestamo($request)
    {
        $prestamo = new Cs_prestamos;
    }

    protected function traerTipoPrestamos()
    {
        $tipo = DB::table('tipo_prestamo')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();

        return $tipo;
    }

    protected function traerTipoPago()
    {
        $tipo = DB::table('tipo_pago')
            ->select([
                'id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();

        return $tipo;
    }

    protected function traerTipos()
    {
        $tPre = $this->traerTipoPrestamos();
        $tPa = $this->traerTipoPago();

        return ['tipo_prestamo' => $tPre, 'tipo_pago' => $tPa];
    }

    protected function traerTipoAbono()
    {
        $tipoA = DB::table('tipo_abono_cuotas')
            ->select([
                'id',
                'tipo_pago_id',
                'descripcion'
            ])
            ->where('activo', 'S')
            ->get();
        return $tipoA;
    }

    protected function traerSocio($rut)
    {
        $socio = DB::table('socios')
            ->select([
                'id',
                'rut',
                DB::raw("concat(nombres,' ',a_paterno,' ',a_materno) as socio"),
                'foto'
            ])
            ->where([
                'rut' => $rut,
                'activo' => 'S',
            ])
            ->get();

        if (!$socio->isEmpty()) {
            return $socio;
        } else {
            return ['estado' => 'failed', 'mensaje' => 'El rut ingresado no pertenece a ningun socio.'];
        }
    }
}
