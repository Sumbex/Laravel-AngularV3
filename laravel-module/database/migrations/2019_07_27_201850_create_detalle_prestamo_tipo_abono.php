<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallePrestamoTipoAbono extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_prestamo_tipo_abono', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('prestamo_abono_id');
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
            $table->bigInteger('monto_ingreso');
            $table->bigInteger('monto_egreso');
            $table->integer('definicion');
            $table->integer('estado');
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
        Schema::dropIfExists('detalle_prestamo_tipo_abono');
    }
}
