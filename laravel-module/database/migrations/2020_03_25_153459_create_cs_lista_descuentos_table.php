<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsListaDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_lista_descuentos', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->text('descripcion');
            $table->char('tipo',1); //P: porcentaje, M: monto 
            // $table->float('porcentaje', 8, 2)->nullable();
            // $table->bigInteger('monto')->nullable();
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
        Schema::dropIfExists('cs_lista_descuentos');
    }
}
