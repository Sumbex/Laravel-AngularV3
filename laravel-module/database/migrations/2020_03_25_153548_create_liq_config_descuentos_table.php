<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiqConfigDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liq_config_descuentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('empleado_id');
            $table->bigInteger('cs_lista_descuentos_id');
            $table->float('porcentaje', 8, 2)->nullable();
            $table->bigInteger('monto')->nullable();
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
        Schema::dropIfExists('liq_config_descuentos');
    }
}
