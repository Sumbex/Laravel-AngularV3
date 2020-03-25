<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LiqEmpleado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liq_empleado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rut_trabajador');
            $table->string('nombre_trabajador');
            $table->string('cargo');
            $table->string('nombre_afp');
            $table->string('nombre_isapre')->unique();
            $table->string('rut_empresa');
            $table->string('nombre_empresa');
            $table->string('direccion_empresa');
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
        Schema::dropIfExists('liq_empleado');
    }
}
