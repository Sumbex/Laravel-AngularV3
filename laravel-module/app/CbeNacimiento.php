<?php

namespace App;

use App\SocioCarga;
use App\SocioBeneficiario;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CbeNacimiento extends Model
{
    protected $table = 'cbe_nacimiento';

    protected function insertar($cbe_id, $socio_id, $get_rut)
    {
        $rut = $this->limpiar($get_rut);

        $verify = $this->verificar_existencia_nacimiento($socio_id, $rut);

        if ($verify) {
            
            $this->socio_id = $socio_id;
            $this->cuenta_bienestar_id = $cbe_id;
            $this->activo = 'S';
            $this->rut_nacimiento = $rut;
            if ($this->save()) {
                return ['estado'=>'failed', 'mensaje'=> 'Item de nacimiento ingresado con exito' ];
            }
            return [ 'estado'=>'failed', 'mensaje'=> 'Error al guardar item' ];
        }
        return [ 'estado'=>'failed', 'mensaje'=> 'El rut del recien nacido ya esta asociado al item NACIMIENTO segun el socio' ];

    }


    public function verificar_existencia_nacimiento($socio_id, $rut)
    {
        $exist_beneficiario = false;
        $existe_carga = false;
        
        $benef = SocioBeneficiario::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'
        ])->first();

        if (!empty($benef)) {
             $exist_beneficiario = true;
        }

        $carga = SocioCarga::where([
                    'socio_id' => $socio_id,
                    'rut' => $rut,
                    'activo' => 'S'

        ])->first();

        if (!empty($carga)) {
             $existe_carga = true;
        }

        if ($exist_beneficiario || $existe_carga) {

            $very = $this->where([
                'activo'=>'S','socio_id'=>$socio_id, 'rut_nacimiento'=>$rut
            ])->first();

            if (empty($very)) {
                return true;
            }else{
                return false;
            }
            
        }
        return false;

        
    }
    protected function mis_beneficios($socio_id){

        $listar = DB::select("SELECT 
                                concat(cbe.dia,' de ',m.descripcion,',',a.descripcion) as fecha,
                                c.rut,
                                concat(c.nombres,' ',c.apellido_paterno,' ',c.apellido_materno) nombre,
                                cbe.descripcion,
                                cbe.monto_egreso     
                            from cbe_nacimiento n
                            inner join cuenta_bienestar cbe on cbe.id = n.cuenta_bienestar_id
                            inner join cargas_legales_socio c on c.rut = n.rut_nacimiento
                            inner join anio a on a.id = cbe.anio_id
                            inner join mes m on m.id = cbe.mes_id
                            where n.socio_id = $socio_id  and cbe.activo = 'S'");
        
        if (count($listar) > 0) {
    		return $listar;
    	}else{
    		return '';
    	}
    }

    function limpiar($s) 
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
