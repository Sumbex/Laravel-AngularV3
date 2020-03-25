<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsArchivadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_archivador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
            $table->text('titulo');
            $table->integer('tipo_archivo_id');
            $table->text('archivo');
            $table->integer('user_crea');
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
        Schema::dropIfExists('cs_archivador');
    }
}
