<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocioConyugeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socio_conyuge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_id');
            $table->text('rut');
            $table->date('fecha_nacimiento');
            $table->text('nombres');
            $table->text('apellido_paterno');
            $table->text('apellido_materno');
            $table->text('direccion');
            $table->integer('comuna_id');
            $table->text('celular');
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
        Schema::dropIfExists('socio_conyuge');
    }
}
