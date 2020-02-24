<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsorcioPagoDiaSueldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consorcio_pago_dia_sueldo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('socio_id');
            $table->date('fecha');
            $table->text('descripcion');
            $table->bigInteger('monto');
            $table->bigInteger('prestamo');
            $table->text('documento')->nullable();
            $table->text('numero_documento');
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
        Schema::dropIfExists('consorcio_pago_dia_sueldo');
    }
}
