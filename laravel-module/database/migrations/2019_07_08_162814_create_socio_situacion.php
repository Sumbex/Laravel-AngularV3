<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocioSituacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socio_situacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('socio_id');
            $table->string('numero_cuenta');
            $table->integer('tipo_cuenta_banco_id');
            $table->string('banco');
            $table->string('isapre_fonasa');
            $table->string('grupo_sangre');
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
        Schema::dropIfExists('socio_situacion');
    }
}
