<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsGastosOperacionalesDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_gastos_operacionales_detalle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cs_cuenta_sindicato_id');
            $table->integer('mes_id');
            $table->integer('anio_id');
            $table->text('descripcion');
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
        Schema::dropIfExists('cs_gastos_operacionales_detalle');
    }
}
