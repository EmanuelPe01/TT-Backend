<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tt_t_DetalleRutina extends Model
{
    use HasFactory;
    protected $table = 'tt_t_detalleRutina';

    protected $fillable = [ 
        'id_rutina',
        'id_ejercicio',
        'cantidad_ejercicio',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id_rutina'
    ];
}
