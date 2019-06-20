<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCuentaSindicato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_sindicato', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('anio_id');
            $table->date('mes_id');
            $table->integer('dia');
            $table->bigInteger('numero_documento');
            $table->text('archivo_documento')->nullable();
            $table->integer('tipo_cuenta_sindicato');
            $table->text('descripcion');
            $table->bigInteger('monto_ingreso')->nullable();
            $table->bigInteger('monto_egreso')->nullable();
            $table->bigInteger('saldo_actual')->nullable();
            $table->integer('definicion');
            $table->integer('user_crea');
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
        Schema::dropIfExists('cuenta_sindicato');
    }
}
