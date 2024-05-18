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
        Schema::create('tt_t_detalleRutina', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ejercicio');
            $table->foreign('id_ejercicio')->references('id')->on('tt_t_detalleEjercicio')->onDelete('cascade');
            $table->unsignedBigInteger('id_rutina');
            $table->foreign('id_rutina')->references('id')->on('tt_t_rutina')->onDelete('cascade');
            $table->integer('cantidad_ejercicio');
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
        Schema::dropIfExists('tt_t_detalleRutina');
    }
};
