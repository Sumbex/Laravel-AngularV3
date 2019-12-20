<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecLeyesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_leyes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('cuerpo');
            $table->text('archivo')->nullable();
            $table->integer('sec_leyes_estado');
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
        Schema::dropIfExists('sec_leyes');
    }
}
