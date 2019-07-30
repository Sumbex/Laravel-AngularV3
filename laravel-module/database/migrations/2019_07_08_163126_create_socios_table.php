<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nombres');
            $table->string('a_paterno');
            $table->string('a_materno');
            $table->text('rut');
            $table->date('fecha_nacimiento')->nullable();
            $table->char('activo', 1);
            $table->text('foto')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_egreso')->nullable();
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
        Schema::dropIfExists('socios');
    }
}
