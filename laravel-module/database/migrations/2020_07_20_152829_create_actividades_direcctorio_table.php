<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesDirecctorioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_actividades_directorio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha');
            $table->string('responsable');
            $table->text('actividad');
            $table->integer('estado_actividad_id');
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
        Schema::dropIfExists('actividades_direcctorio');
    }
}
