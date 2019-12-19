<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsorcioDirectivaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consorcio_directiva', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('directiva_id');
            $table->integer('anio_id_inicio');
            $table->integer('anio_id_fin');
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
        Schema::dropIfExists('consorcio_directiva');
    }
}
