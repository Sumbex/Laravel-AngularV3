<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecReunionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_reuniones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_termino')->nullable();
            $table->integer('estado_reunion_id');
            $table->string('cabeza');
            $table->text('cuerpo');
            $table->integer('tipo_reunion_id');
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
        Schema::dropIfExists('sec_reuniones');
    }
}
