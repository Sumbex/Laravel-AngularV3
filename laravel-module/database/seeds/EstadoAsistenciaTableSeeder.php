<?php

use Illuminate\Database\Seeder;

class EstadoAsistenciaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sec_estado_asistencia')->insert([
            ['id' => '1', 'descripcion' => 'Presente', 'tipo_estado' => 'Asistente', 'activo' => 'S'],
            ['id' => '2', 'descripcion' => 'Ausente', 'tipo_estado' => 'Inasistente', 'activo' => 'S'],
            ['id' => '3', 'descripcion' => 'Justificado', 'tipo_estado' => 'Asistente', 'activo' => 'S']
        ]);
    }
}
