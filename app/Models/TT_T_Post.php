<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TT_T_Post extends Model
{
    use HasFactory;

    protected $table = "tt_t_posts";

    protected $fillable = [
        'id_inscripcion',
        'mensaje',
        'tipo_usuario'
    ];

    protected $hidden = [
        'id',
        'id_inscripcion',
        'updated_at',
    ];
}
