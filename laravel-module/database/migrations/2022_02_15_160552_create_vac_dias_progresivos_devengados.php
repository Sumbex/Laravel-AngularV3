<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacDiasProgresivosDevengados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vac_dias_progresivos_devengados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('vac_historial_id');
            $table->bigInteger('vac_solicitud_id')->nullable();
            $table->bigInteger('anio');
            $table->integer('mes');
            $table->float('dias_progresivos_devengados')->nullable();
            $table->float('dias_progresivos_solicitud')->nullable();
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
        Schema::dropIfExists('vac_dias_progresivos_devengados');
    }
}
