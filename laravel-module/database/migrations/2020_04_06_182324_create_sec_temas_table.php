<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecTemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_temas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_inicio');
            $table->date('fecha_termino')->nullable();
            $table->string('titulo');
            $table->text('descripcion');
            $table->integer('estado_tema_id');
            $table->integer('estado_aprobacion_id')->nullable();
            $table->string('motivo')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('sec_temas');
    }
}
