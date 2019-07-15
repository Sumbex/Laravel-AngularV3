<?php

use Illuminate\Database\Seeder;

class TipoAbonoCuotasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_abono_cuotas')->insert([
            [ 'id' => '1', 'tipo_pago_id' => 2,'descripcion' => 'Dia de Sueldo', 'activo'=>'s' ],
            [ 'id' => '2', 'tipo_pago_id' => 2,'descripcion' => 'Termino de Conflicto', 'activo'=>'s' ],
            [ 'id' => '3', 'tipo_pago_id' => 2,'descripcion' => 'Trimestral', 'activo'=>'s' ]
        ]);
    }
}
