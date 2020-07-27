<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecDesarrolloAsambleaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_desarrollo_asamblea', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_crea');
            $table->text('encabezado');
            $table->text('tabla')->nullable();
            $table->text('resumen_anterior')->nullable();
            $table->text('rendicion_cuentas')->nullable();
            $table->text('rendicion_camping')->nullable();
            $table->text('rendicion_bienestar')->nullable();
            $table->text('rendicion_directorio')->nullable();
            $table->text('ayuda_aporte')->nullable();
            $table->text('socios_nuevos')->nullable();
            $table->text('varios')->nullable();

            $table->integer('total_socios')->nullable();
            $table->integer('total_asistidos')->nullable();
            $table->integer('total_justificados')->nullable();
            $table->integer('sin_justificar')->nullable();

            $table->string('tipo');
            $table->string('fecha');
            $table->string('hora');
            $table->string('ubicacion');
            $table->char('activo',1);
            $table->integer('estado');

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
        Schema::dropIfExists('sec_desarrollo_asamblea');
    }
}
