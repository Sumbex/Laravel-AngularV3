<?php

use Illuminate\Database\Seeder;

class MesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mes')->insert([
        	[ 'id' => '1', 'descripcion' => 'Enero', 'activo'=>'s' ],
        	[ 'id' => '2', 'descripcion' => 'Febrero', 'activo'=>'s' ],
        	[ 'id' => '3', 'descripcion' => 'Marzo', 'activo'=>'s' ],
        	[ 'id' => '4', 'descripcion' => 'Abril', 'activo'=>'s' ],
        	[ 'id' => '5', 'descripcion' => 'Mayo', 'activo'=>'s' ],
        	[ 'id' => '6', 'descripcion' => 'Junio', 'activo'=>'s' ],
        	[ 'id' => '7', 'descripcion' => 'Julio', 'activo'=>'s' ],
        	[ 'id' => '8', 'descripcion' => 'Agosto', 'activo'=>'s' ],
        	[ 'id' => '9', 'descripcion' => 'Septiembre', 'activo'=>'s' ],
        	[ 'id' => '10', 'descripcion' => 'Octubre', 'activo'=>'s' ],
        	[ 'id' => '11', 'descripcion' => 'Noviembre', 'activo'=>'s' ],
        	[ 'id' => '12', 'descripcion' => 'Diciembre', 'activo'=>'s' ]
        ]);
    }
}
