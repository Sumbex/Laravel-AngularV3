<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcuerdosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_acuerdos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo');
            $table->dateTime('fecha');
            $table->string('contenido');
            $table->string('descripcion_cambio_estado');
            $table->integer('id_tipo_acuerdo');
            $table->integer('id_estado_acuerdo');
            $table->integer('id_usuario');
            $table->char('activo',1);
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
        Schema::dropIfExists('acuerdos');
    }
}
