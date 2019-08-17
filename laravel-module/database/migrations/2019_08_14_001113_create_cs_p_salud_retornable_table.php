<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsPSaludRetornableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_salud_retornable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('prestamo_id');
            $table->bigInteger('monto_restante')->nullable();
            $table->integer('numero_cuota')->nullable();
            $table->integer('cuotas')->nullable();
            $table->char('abono',1);
            $table->bigInteger('monto_dia_sueldo')->nullable();
            $table->bigInteger('monto_trimestral')->nullable();
            $table->bigInteger('monto_termino_conflicto')->nullable();
            $table->bigInteger('ingreso')->nullable();
            $table->bigInteger('egreso')->nullable();
            $table->integer('definicion');
            $table->string('estado_prestamo');
            $table->string('estado_abono');
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
            $table->bigInteger('user_id');//usuario que tramita el pago de la cuota
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
        Schema::dropIfExists('p_salud_retornable');
    }
}
