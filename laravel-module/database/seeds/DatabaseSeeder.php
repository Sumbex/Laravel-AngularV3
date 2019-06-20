<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AnioTableSeeder::class);
        $this->call(DefinicionTableSeeder::class);
        $this->call(MesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(TipoCuentaSindicatoTableSeeder::class);
    }
}
