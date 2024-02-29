<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tt_t_inscripcion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user_cliente')->unique();
            $table->foreign('id_user_cliente')->references('id')->on('tt_t_usuario')->onDelete('cascade');
            $table->unsignedBigInteger('id_user_entrenador');
            $table->foreign('id_user_entrenador')->references('id')->on('tt_t_usuario')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->float('peso_maximo');
            $table->boolean('estado');
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
        Schema::dropIfExists('tt_t_inscripcion');
    }
};
