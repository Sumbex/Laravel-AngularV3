<?php

use Illuminate\Database\Seeder;

class ListaHaberesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cs_lista_haberes')->insert([
            [ 'id' => '1', 'descripcion' => 'Día trabajado' ,'tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '2', 'descripcion' => 'Hora extra' ,'tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '3', 'descripcion' => 'Gratificación legal','tipo'=>'P', 'activo'=>'S' ],
            [ 'id' => '4', 'descripcion' => 'Bono locomoción (x día)','tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '5', 'descripcion' => 'Bono colación (x día)','tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '6', 'descripcion' => 'Asignación familiar','tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '7', 'descripcion' => 'Viatico','tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '8', 'descripcion' => 'Bano extra','tipo'=>'M', 'activo'=>'S' ],
            [ 'id' => '9', 'descripcion' => 'Vacaciones','tipo'=>'M', 'activo'=>'S' ],
        	
        ]);
    }
}
