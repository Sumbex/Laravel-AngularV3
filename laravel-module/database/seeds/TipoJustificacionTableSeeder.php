<?php

use Illuminate\Database\Seeder;

class TipoJustificacionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sec_tipo_justificacion')->insert([
            ['id' => '1', 'descripcion' => 'Turno', 'tipo_justificacion' => null, 'activo' => 'S'],
            ['id' => '2', 'descripcion' => 'Vacaciones', 'tipo_justificacion' => 'Filtro', 'activo' => 'S'],
            ['id' => '3', 'descripcion' => 'Licencia', 'tipo_justificacion' => 'Filtro', 'activo' => 'S'],
            ['id' => '4', 'descripcion' => 'Medico', 'tipo_justificacion' => null, 'activo' => 'S'],
            ['id' => '5', 'descripcion' => 'Fuera Ciudad', 'tipo_justificacion' => null, 'activo' => 'S']
        ]);
    }
}
