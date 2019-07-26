<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleInteresPrestamo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_interes_prestamo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('interes_prestamo_id');
            $table->bigInteger('interes_mensual');
            $table->char('activo', 1);
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
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
        Schema::dropIfExists('detalle_interes_prestamo');
    }
}
