<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TT_T_Resultados extends Model
{
    use HasFactory;

    protected $table = "tt_t_resultados";

    protected $fillable = [
        'id_rutina',
        'rondas',
        'tiempo',
        'comentarios'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
