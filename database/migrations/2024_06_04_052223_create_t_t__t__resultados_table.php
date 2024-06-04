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
        Schema::create('tt_t_resultados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rutina');
            $table->foreign('id_rutina')->references('id')->on('tt_t_rutina')->onDelete('cascade');
            $table->integer('rondas');
            $table->string('tiempo');
            $table->string('comentarios');
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
        Schema::dropIfExists('t_t__t__resultados');
    }
};
