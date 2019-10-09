<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_asistencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('reunion_id');
            $table->integer('socio_id');
            $table->integer('estado_asistencia_id');
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
        Schema::dropIfExists('sec_asistencia');
    }
}
