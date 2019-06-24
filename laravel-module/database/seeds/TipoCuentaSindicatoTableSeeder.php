<?php

use Illuminate\Database\Seeder;

class TipoCuentaSindicatoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_cuenta_sindicato')->insert([
            [ 'id' => '1', 'descripcion' => 'Fijo', 'activo'=>'s' ],
            [ 'id' => '2', 'descripcion' => 'Variable', 'activo'=>'s' ]
        ]);
    }
}
