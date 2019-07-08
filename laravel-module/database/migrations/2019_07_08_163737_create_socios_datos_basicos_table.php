<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSociosDatosBasicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socios_datos_basicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_id');
            $table->text('direccion');
            $table->text('direccion_2')->nullable();
            $table->bigInteger('telefono')->nullable();
            $table->bigInteger('celular')->nullable();
            $table->string('anexo')->nullable();
            $table->string('email_1');
            $table->string('email_2')->nullable();
            $table->text('cargo_planta')->nullable();
            $table->integer('cargo_comision_sindicato')->nullable();
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
        Schema::dropIfExists('socios_datos_basicos');
    }
}
