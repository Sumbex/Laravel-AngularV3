<?php

use Illuminate\Database\Seeder;

class PermisosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permiso')->insert([
            [ 'id' => '1', 'descripcion' => 'Admin', 'activo'=>'s' ]
        ]);
    }
}
