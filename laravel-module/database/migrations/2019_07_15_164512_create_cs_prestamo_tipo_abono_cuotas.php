<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsPrestamoTipoAbonoCuotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_prestamo_tipo_abono_cuotas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cs_prestamo_id');
            $table->integer('tipo_abono_coutas_id');
            $table->bigInteger('monto');
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
        Schema::dropIfExists('cs_prestamo_tipo_abono_cuotas');
    }
}
