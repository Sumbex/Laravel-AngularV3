<?php

use Illuminate\Database\Seeder;

class PrestamoEstadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estado_prestamo')->insert([
            [ 'id' => '1', 'descripcion' => 'Vigente', 'activo'=>'S' ],
            [ 'id' => '2', 'descripcion' => 'Pagado', 'activo'=>'S' ],
            [ 'id' => '3', 'descripcion' => 'Cancelado', 'activo'=>'S' ]
        ]);
    }
}
