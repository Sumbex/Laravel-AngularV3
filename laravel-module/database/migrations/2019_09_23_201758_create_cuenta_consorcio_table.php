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
            $table->bigInteger('monto_mes_1')->nullable();
            $table->bigInteger('monto_mes_2')->nullable();
            $table->bigInteger('monto_mes_3')->nullable();
            $table->bigInteger('monto_mes_4')->nullable();
            $table->bigInteger('monto_mes_5')->nullable();
            $table->bigInteger('monto_mes_6')->nullable();
            $table->bigInteger('monto_mes_7')->nullable();
            $table->bigInteger('monto_mes_8')->nullable();
            $table->bigInteger('monto_mes_9')->nullable();
            $table->bigInteger('monto_mes_10')->nullable();
            $table->bigInteger('monto_mes_11')->nullable();
            $table->bigInteger('monto_mes_12')->nullable();
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
