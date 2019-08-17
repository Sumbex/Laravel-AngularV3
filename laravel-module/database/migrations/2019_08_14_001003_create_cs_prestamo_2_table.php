<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsPrestamo2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_prestamo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('descripcion_prestamo');
            $table->text('transferencia_bancaria');
            $table->text('archivo')->nullable();
            $table->integer('tipo_prestamo');
            $table->integer('definicion');
            $table->bigInteger('egreso')->nullable();
            $table->bigInteger('ingreso')->nullable();
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
            $table->bigInteger('user_id');
            $table->bigInteger('socio_id');
            $table->integer('cuotas')->nullable();
            $table->char('estado_prestamo');
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
        Schema::dropIfExists('cs_prestamo');
    }
}
