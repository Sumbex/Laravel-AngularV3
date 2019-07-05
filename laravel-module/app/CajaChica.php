<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CuentaSindicatoController;

class CajaChica extends Model
{
    protected $table = "cs_caja_chica";

    protected function div_fecha($value)
    {
        $fecha = $value;
        $ano = substr($fecha, -10, 4);
        $mes = substr($fecha, -5, 2);
        $dia = substr($fecha, -2, 2);
        return [
            'anio' => $ano, 'mes' => $mes, 'dia' => $dia
        ];
    }
    protected function anio_tipo_id($value)
    {
        return DB::table('anio')->where('descripcion', $value)->first();
    }

    protected function mes_tipo_id($value)
    {
        return DB::table('mes')->where('id', $value)->first();
    }

    protected function validarNumDoc($numDoc){
        $caja = DB::table('cs_caja_chica')
            ->select('numero_documento')
            ->where([
                'numero_documento' => $numDoc,
                'activo' => 'S',
            ])
            ->first();

            if (empty($caja)) {
                return ['estado' => 'success', 'mensaje' => 'Todo bien.'];
            } else {
                return ['estado' => 'failed', 'mensaje' => 'El numero de documento ya existe.'];
            }
    }

    protected function ingresarCajaChica($request)
    {
        $caja = new CajaChica;

        $fecha = $this->div_fecha($request->fecha);

        $anio = $this->anio_tipo_id($fecha['anio']);
        $mes = $this->mes_tipo_id($fecha['mes']);

        $existe = $this->existeCajaChica($anio->id, $mes->id);
        $nDoc = $this->validarNumDoc($request->numero_documento);

        if ($existe['estado'] == 'success') {

            if($nDoc['estado'] == 'success'){

                $caja->anio_id = $anio->id;
                $caja->mes_id = $fecha['mes'];
                $caja->dia = $fecha['dia'];
                $caja->numero_documento = $request->numero_documento;
                $caja->archivo_documento = '/doc/archivo.pdf';
                $caja->descripcion = $request->descripcion;
    
                switch ($request->definicion) {
                    case '1':
                        $caja->monto_ingreso = $request->monto;
                        break;
                    case '2':
                        $caja->monto_egreso = $request->monto;
                        break;
                }
    
                $caja->definicion = $request->definicion;
                $caja->user_crea = Auth::user()->id;
                $caja->activo = "S";
    
                if ($caja->save()) {
                    return ['estado' => 'success', 'mensaje' => 'Insertado'];
                } else {
                    return ['estado' => 'failed', 'mensaje' => 'No Insertado'];
                }
            }else{
                return $nDoc;
            }
            
        } else {
            return $existe;
        }
    }

    protected function existeCajaChica($anio, $mes)
    {
        $cSindicato = DB::table('cuenta_sindicato')
            ->select([
                'tipo_cuenta_sindicato',
                'monto_egreso',
                'anio_id',
                'mes_id'
            ])
            ->where([
                'cuenta_sindicato.activo' => 'S',
                'cuenta_sindicato.anio_id' => $anio,
                'cuenta_sindicato.mes_id' => $mes,
                'cuenta_sindicato.tipo_cuenta_sindicato' => 3
            ])
            ->get();

        if (!$cSindicato->isEmpty()) {
            return ['estado' => 'success', 'mensaje' => 'La Caja Chica de este mes existe'];
        } else {
            return ['estado' => 'failed', 'mensaje' => 'Aun no ha realizado el ingreso a Caja Chica este mes.'];
        }
    }

    protected function traerCajaChica($anio, $mes)
    {
        $caja = DB::table('cs_caja_chica as cc')
            ->select([
                'cc.id',
                DB::raw("concat(cc.dia,' de ',m.descripcion,',',a.descripcion) as fecha"),
                'cc.numero_documento',
                'cc.archivo_documento',
                'cc.descripcion',
                'cc.monto_ingreso',
                'cc.monto_egreso',
                'cc.definicion'
            ])
            ->join('anio as a', 'a.id', 'anio_id')
            ->join('mes as m', 'm.id', 'mes_id')
            ->where([
                'cc.activo' => 'S',
                'cc.anio_id' => $anio,
                'cc.mes_id' => $mes,
            ])
            ->get();

        return $caja;
    }

    protected function totalIngEgre($anio, $mes)
    {
        $total = 0;
        $caja = DB::table('cs_caja_chica')
            ->select(DB::raw('sum(monto_ingreso) as total_ingreso, sum(monto_egreso) as total_egreso'))
            ->where([
                'activo' => 'S',
                'anio_id' => $anio,
                'mes_id' => $mes,
            ])
            ->get();

        return $caja;
    }
}
