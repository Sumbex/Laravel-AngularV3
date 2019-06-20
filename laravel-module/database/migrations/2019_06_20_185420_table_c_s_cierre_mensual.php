<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCSCierreMensual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_s_cierre_mensual', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('anio_id');
            $table->date('mes_id');
            $table->bigInteger('inicio_mensual')->nullable();
            $table->bigInteger('cierre_mensual')->nullable();
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
        //
    }
}
