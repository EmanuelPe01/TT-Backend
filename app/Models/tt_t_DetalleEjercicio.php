<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\tt_t_tipoEjercicio as tipoEjercicio;
use App\Models\tt_t_unidadesMedida as unidadMedida;

class tt_t_DetalleEjercicio extends Model
{
    use HasFactory;
    protected $table = "tt_t_detalleEjercicio";

    protected $fillable = [
        'id_tipo_ejercicio',
        'id_unidad_medida',
        'nombre_ejercicio',
        'demo_ejercicio'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function tipoEjercicio()
    {
        return $this->belongsTo(tipoEjercicio::class, 'id_tipo_ejercicio');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(unidadMedida::class, 'id_unidad_medida');
    }
}
