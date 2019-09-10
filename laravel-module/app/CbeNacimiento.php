<?php

namespace App;

use App\SocioCarga;
use App\SocioBeneficiario;
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
                return [ 'mensaje'=> 'El rut del recien nacido puede que no exista en beneficios o ya esta asociado al item NACIMIENTO segun el socio' ];
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
