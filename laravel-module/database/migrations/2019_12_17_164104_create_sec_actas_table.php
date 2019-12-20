<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecActasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_actas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_crea');
            $table->text('encabezado');
            $table->text('miembros');
            $table->text('orden_dia');
            $table->text('detalle_orden_dia');
            $table->string('fecha');
            $table->string('hora');
            $table->string('ubicacion');
            $table->text('dirigente');
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
        Schema::dropIfExists('sec_actas');
    }
}
