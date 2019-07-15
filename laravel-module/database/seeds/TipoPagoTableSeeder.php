<?php

use Illuminate\Database\Seeder;

class TipoPagoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_pago')->insert([
            [ 'id' => '1', 'descripcion' => 'Cuotas', 'activo'=>'s' ],
            [ 'id' => '2', 'descripcion' => 'Abono mas Cuotas', 'activo'=>'s' ]
        ]);
    }
}
