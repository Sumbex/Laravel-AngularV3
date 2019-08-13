<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargasLegalesSocioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargas_legales_socio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_id');
            $table->integer('tipo_carga_id');
            $table->text('rut');
            $table->date('fecha_nacimiento');
            $table->text('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->text('direccion');
            $table->text('celular');
            $table->text('establecimiento')->nullable();
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
        Schema::dropIfExists('cargas_legales_socio');
    }
}
