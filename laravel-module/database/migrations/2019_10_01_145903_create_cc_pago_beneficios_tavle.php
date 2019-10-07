<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCcPagoBeneficiosTavle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cc_pago_beneficios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cuenta_consorcio_id');
            $table->integer('mes_id');
            $table->text('descripcion');
            $table->string('porcentaje');
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
        Schema::dropIfExists('cc_pago_beneficios');
    }
}
