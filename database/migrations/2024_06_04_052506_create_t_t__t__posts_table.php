<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tt_t_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inscripcion');
            $table->foreign('id_inscripcion')->references('id')->on('tt_t_inscripcion')->onDelete('cascade');
            $table->string('mensaje');
            $table->enum('tipo_usuario', ['cliente', 'entrenador']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_t__t__posts');
    }
};
