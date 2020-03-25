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
        $this->call(ListaHaberesSeeder::class);
        $this->call(ListaDescuentosSeeder::class);
        //  $this->call(AnioTableSeeder::class);
        // $this->call(DefinicionTableSeeder::class);
        // $this->call(MesTableSeeder::class);
        // $this->call(TipoCuentaSindicatoTableSeeder::class);
        // $this->call(PermisosTableSeeder::class);
        // $this->call(EstadoAsistenciaTableSeeder::class);
        // $this->call(EstadoReunionTableSeeder::class);
        // $this->call(TipoJustificacionTableSeeder::class);
        // $this->call(TipoReunionTableSeeder::class);
    }
}
