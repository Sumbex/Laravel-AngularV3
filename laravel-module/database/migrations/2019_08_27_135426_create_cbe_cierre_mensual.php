<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCbeCierreMensual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbe_cierre_mensual', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('inicio_mensual');
            $table->bigInteger('cierre_mensual')->nullable();
            $table->char('activo', 1);
            $table->integer('mes_id');
            $table->integer('anio_id');
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
        Schema::dropIfExists('cbe_cierre_mensual');
    }
}
