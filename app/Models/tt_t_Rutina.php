<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tt_t_Rutina extends Model
{
    use HasFactory;
    protected $table = "tt_t_rutina";

    protected $fillable = [ 
        'id_inscripcion',
        'fecha_rutina',
        'rondas',
        'tiempo',
        'peso',
        'halterofilia',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    public function detalleRutina()
    {
        return $this->hasMany(tt_t_detalleRutina::class, 'id_rutina');
    }
}
