<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadoDiaSueldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_dia_sueldos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuenta_sindicato_id');
            $table->char('egreso_total', 1);
            $table->integer('directiva_id');
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
        Schema::dropIfExists('estado_dia_sueldos');
    }
}
