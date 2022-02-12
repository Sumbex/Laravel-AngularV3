<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vac_historial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('liq_empleado_id');
            $table->float('d_basicos_devengados', 8, 2)->nullable();
            $table->float('d_progresivos_devengados', 8, 2)->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_base_progresiva')->nullable();
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
        Schema::dropIfExists('vac_historial');
    }
}
