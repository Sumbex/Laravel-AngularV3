<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePAporteEconomicoRetornableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_apuro_economico_retornable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('prestamo_id');
            $table->integer('numero_cuota')->nullable();
            $table->integer('cuotas')->nullable();
            $table->bigInteger('interes_mensual');
            $table->bigInteger('interes_total');
            $table->integer('definicion');
            $table->bigInteger('ingreso')->nullable();
            $table->bigInteger('egreso')->nullable();
            $table->bigInteger('monto_restante');
            $table->integer('dia');
            $table->integer('mes_id');
            $table->integer('anio_id');
            $table->bigInteger('user_id');
            $table->char('activo',1);
            $table->string('estado_cuota');
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
        Schema::dropIfExists('p_apuro_economico_retornable');
    }
}
