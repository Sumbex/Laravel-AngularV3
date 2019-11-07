<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsGastosOperacionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_gastos_operacionales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dia');
            $table->integer('mes_id');
            $table->integer('anio_id');
            $table->text('numero_documento');
            $table->text('archivo_documento');
            $table->integer('tipo_cuenta_sindicato');
            $table->text('descripcion');
            $table->integer('definicion');
            $table->bigInteger('monto_ingreso')->nullable();
            $table->bigInteger('monto_egreso')->nullable();
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
        Schema::dropIfExists('cs_gastos_operacionales');
    }
}
