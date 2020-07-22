<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSecDesarrolloAsambleaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sec_desarrollo_asamblea', function (Blueprint $table) {
               $table->text('puntos_a_tratar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sec_desarrollo_asamblea', function (Blueprint $table) {
            //
        });
    }
}
