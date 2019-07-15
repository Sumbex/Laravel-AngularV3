<?php

use Illuminate\Database\Seeder;

class TipoPrestamoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_prestamo')->insert([
            [ 'id' => '1', 'descripcion' => 'Salud', 'activo'=>'s' ],
            [ 'id' => '2', 'descripcion' => 'Libre', 'activo'=>'s' ],
            [ 'id' => '3', 'descripcion' => 'No Retornable', 'activo'=>'s' ]
        ]);
    }
}
