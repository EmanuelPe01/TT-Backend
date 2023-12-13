<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('TT_T_Rol', function (Blueprint $table) {
            $table->id();
            $table->string('rol_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('TT_T_Rol');
    }
};
