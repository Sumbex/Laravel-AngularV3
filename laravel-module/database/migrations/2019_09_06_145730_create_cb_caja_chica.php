<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCbCajaChica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cb_caja_chica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
            $table->text('numero_documento');
            $table->text('archivo_documento')->nullable();
            $table->text('descripcion');
            $table->bigInteger('monto_ingreso')->nullable();
            $table->bigInteger('monto_egreso')->nullable();
            $table->integer('definicion');
            $table->integer('user_crea');
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
        Schema::dropIfExists('cb_caja_chica');
    }
}
