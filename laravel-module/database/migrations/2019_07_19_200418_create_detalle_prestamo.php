<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallePrestamo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_prestamo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('prestamo_id');
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
            $table->bigInteger('monto');
            //$table->bigInteger('monto_ingreso')->nullable();
            //$table->bigInteger('monto_egreso')->nullable();
            $table->char('activo', 1);
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
        Schema::dropIfExists('detalle_prestamo');
    }
}
