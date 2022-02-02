<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vac_solicitud', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vac_historial_id');
            $table->date('fecha_solicitud');
            $table->date('fecha_inicio');
            $table->date('fecha_termino');
            $table->float('dias_legales', 8, 2)->nullable();
            $table->float('dias_progresivos', 8, 2)->nullable();
            $table->text('documento')->nullable();
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
        Schema::dropIfExists('vac_solicitud');
    }
}
