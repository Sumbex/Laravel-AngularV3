<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecVotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_votos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tema_id');
            $table->integer('socio_id');
            $table->integer('voto_id');
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
        Schema::dropIfExists('sec_votos');
    }
}
