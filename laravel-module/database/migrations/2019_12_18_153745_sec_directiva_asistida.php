<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SecDirectivaAsistida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_directiva_asistida', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sec_directorio_id');
            $table->text('sec_directiva_id');
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
        Schema::dropIfExists('sec_directiva_asistida');
    }
}
