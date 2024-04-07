<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tt_t_TipoEjercicio extends Model
{
    use HasFactory;
    protected $table = 'tt_t_tipoEjercicio';

    protected $fillable = [
        'nombre_tipo'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
