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
        Schema::create('tt_t_detalleEjericio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_ejercicio');
            $table->foreign('id_tipo_ejercicio')->references('id')->on('tt_t_tipoEjercicio')->onDelete('cascade');
            $table->string('nombre_ejercicio');
            $table->string('demo_ejercicio');
            $table->string('unidad_medida');
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
        Schema::dropIfExists('tt_t_detalleEjericio');
    }
};
