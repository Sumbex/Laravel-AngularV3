<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCbeGastosMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbe_gastos_medicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_id');
            $table->bigInteger('cuenta_bienestar_id');
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
        Schema::dropIfExists('cbe_gastos_medicos');
    }
}
