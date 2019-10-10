<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecJustificacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_justificacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('reunion_id');
            $table->integer('socio_id');
            $table->text('descripcion')->nullable();
            $table->integer('tipo_justificacion_id');
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
        Schema::dropIfExists('sec_justificacion');
    }
}
