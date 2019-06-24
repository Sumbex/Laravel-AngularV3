<?php

use Illuminate\Database\Seeder;

class DefinicionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('definicion')->insert([
            [ 'id' => '1', 'descripcion' => 'Ingreso', 'activo'=>'s' ],
            [ 'id' => '2', 'descripcion' => 'Egreso', 'activo'=>'s' ]
        ]);
    }
}
