<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacDiasBasicosDevengadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vac_dias_basicos_devengados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('vac_historial_id');
            $table->bigInteger('anio');
            $table->integer('mes');
            $table->float('dias_basicos')->nullable();
            $table->float('dias_progresivos')->nullable();//estos son los dias legales de la solicitud no progresivos
            $table->char('activo',1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vac_dias_basicos_devengados');
    }
}
