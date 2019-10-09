<?php

use Illuminate\Database\Seeder;

class TipoReunionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sec_tipo_reunion')->insert([
            ['id' => '1', 'descripcion' => 'General', 'activo' => 'S'],
            ['id' => '2', 'descripcion' => 'Eleccion', 'activo' => 'S'],
            ['id' => '3', 'descripcion' => 'Votacion', 'activo' => 'S']
        ]);
    }
}
