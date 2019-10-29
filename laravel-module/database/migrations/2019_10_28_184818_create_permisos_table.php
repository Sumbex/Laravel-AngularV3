<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rut');
            $table->char('ingresar_cierre_inicio_mes',1)->nullable();
            $table->char('ingresar_cs',1)->nullable();
            $table->char('modificar_cs',1)->nullable();
            $table->char('ingresar_total_camping',1)->nullable();
            $table->char('modificar_detalle_camping',1)->nullable();
            $table->char('ingresar_socio',1)->nullable();
            $table->char('modificar_socio',1)->nullable();
            $table->char('ingresar_socio_beneficios',1)->nullable();
            $table->char('modificar_socio_beneficios',1)->nullable();
            $table->char('ingresar_cch',1)->nullable();
            $table->char('modificar_cch',1)->nullable();
            $table->char('ingresar_cb',1)->nullable();
            $table->char('modificar_cb',1)->nullable();
            $table->char('calcular_descuento_cc',1)->nullable();
            $table->char('aplicar_descuento_cc',1)->nullable();
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
        Schema::dropIfExists('permisos');
    }
}
