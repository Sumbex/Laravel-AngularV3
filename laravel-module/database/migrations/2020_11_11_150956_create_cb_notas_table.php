<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCbNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cb_notas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cuenta_id');
            $table->integer('anio_id');
            $table->integer('mes_id');
            $table->integer('dia');
            $table->text('descripcion');
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
        Schema::dropIfExists('cb_notas');
    }
}
