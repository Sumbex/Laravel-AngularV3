<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos_salud_retornable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('prestamo_id');
            $table->bigInteger('monto')->nullable();
            $table->char('abono_tipo');
            $table->integer('socio_id');
            $table->char('activo');
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
        Schema::dropIfExists('abonos_salud_retornable');
    }
}
