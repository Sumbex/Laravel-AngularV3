<?php

use Illuminate\Database\Seeder;

class AnioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('anio')->insert([
        	[ 'id' => '1', 'descripcion' => '2019', 'activo'=>'s' ]     
        ]);
    }
}
