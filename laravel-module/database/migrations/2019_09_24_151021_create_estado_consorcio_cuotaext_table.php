<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadoConsorcioCuotaextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_consorcio_cuota_extra', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_id');
            $table->string('descripcion');
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
        Schema::dropIfExists('estado_consorcio_cuota_extra');
    }
}
