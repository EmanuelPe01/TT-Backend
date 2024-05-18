<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tt_t_unidadesMedida extends Model
{
    use HasFactory;
    protected $table = 'tt_t_unidadesMedida';

    protected $fillable = [
        'unidad_medida'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
