<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsPrestamos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_prestamos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('socio_id');
            $table->integer('dia');
            $table->integer('mes_id');
            $table->integer('anio_id');
            $table->text('numero_documento');
            $table->text('archivo_documento');
            $table->integer('tipo_cuenta_sindicato');
            $table->text('descripcion');
            $table->bigInteger('monto_ingreso')->nullable();
            $table->bigInteger('monto_egreso')->nullable();
            $table->bigInteger('saldo_actual')->nullable();
            $table->integer('definicion');
            $table->integer('tipo_prestamo_id');
            $table->integer('tipo_pago_id');
            $table->bigInteger('user_crea');
            $table->bigInteger('cuota');
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
        Schema::dropIfExists('cs_prestamos');
    }
}
