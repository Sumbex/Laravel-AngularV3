<?php

use Illuminate\Database\Seeder;

class EstadoReunionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sec_estado_reunion')->insert([
            ['id' => '1', 'descripcion' => 'Publicada', 'tipo_estado' => 'Activo', 'activo' => 'S'],
            ['id' => '2', 'descripcion' => 'Iniciada', 'tipo_estado' => 'Activo', 'activo' => 'S'],
            ['id' => '3', 'descripcion' => 'Cerrada', 'tipo_estado' => 'Activo', 'activo' => 'S'],
            ['id' => '4', 'descripcion' => 'Finalizada', 'tipo_estado' => 'Inactivo', 'activo' => 'S'],
            ['id' => '5', 'descripcion' => 'Archivada', 'tipo_estado' => 'Inactivo', 'activo' => 'S']
        ]);
    }
}
