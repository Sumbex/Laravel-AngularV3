<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListaDescuentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cs_lista_descuentos')->insert([
            [ 'id' => '1', 'descripcion' => 'Cotización AFP' ,'tipo'=>'P', 'activo'=>'S' ],
            [ 'id' => '2', 'descripcion' => 'Cotización salud' ,'tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '3', 'descripcion' => 'Plan de salud','tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '4', 'descripcion' => 'Seguro de cesantia','tipo'=>'P', 'activo'=>'S' ],
            [ 'id' => '5', 'descripcion' => 'Anticipo','tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '6', 'descripcion' => 'Prestamo','tipo'=>'M', 'activo'=>'S' ],
        	
        ]);
    }
}
