<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tt_t_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rol')->references('id')->on('tt_t_rol');
            $table->string('name');
            $table->string('firstSurname');
            $table->string('secondSurname');
            $table->string('telephone')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tt_t_usuario');
    }
};
