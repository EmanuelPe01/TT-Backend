<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('tt_t_rol', function (Blueprint $table) {
            $table->id();
            $table->string('rol_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tt_t_rol');
    }
};
