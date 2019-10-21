<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuentaConsorcioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_consorcio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_id');
            $table->char('vinculado',1)->nullable();
            $table->integer('pago_mes_atual')->nullable();
            $table->integer('anio_id');
            $table->bigInteger('monto_mes_ds_1')->nullable();
            $table->bigInteger('monto_mes_cex_1')->nullable();
            $table->bigInteger('monto_mes_ds_2')->nullable();
            $table->bigInteger('monto_mes_cex_2')->nullable();
            $table->bigInteger('monto_mes_ds_3')->nullable();
            $table->bigInteger('monto_mes_cex_3')->nullable();
            $table->bigInteger('monto_mes_ds_4')->nullable();
            $table->bigInteger('monto_mes_cex_4')->nullable();
            $table->bigInteger('monto_mes_ds_5')->nullable();
            $table->bigInteger('monto_mes_cex_5')->nullable();
            $table->bigInteger('monto_mes_ds_6')->nullable();
            $table->bigInteger('monto_mes_cex_6')->nullable();
            $table->bigInteger('monto_mes_ds_7')->nullable();
            $table->bigInteger('monto_mes_cex_7')->nullable();
            $table->bigInteger('monto_mes_ds_8')->nullable();
            $table->bigInteger('monto_mes_cex_8')->nullable();
            $table->bigInteger('monto_mes_ds_9')->nullable();
            $table->bigInteger('monto_mes_cex_9')->nullable();
            $table->bigInteger('monto_mes_ds_10')->nullable();
            $table->bigInteger('monto_mes_cex_10')->nullable();
            $table->bigInteger('monto_mes_ds_11')->nullable();
            $table->bigInteger('monto_mes_cex_11')->nullable();
            $table->bigInteger('monto_mes_ds_12')->nullable();
            $table->bigInteger('monto_mes_cex_12')->nullable();
            $table->bigInteger('acumulado_anterior_socio')->nullable();//item nuevo
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
        Schema::dropIfExists('cuenta_consorcio');
    }
}
