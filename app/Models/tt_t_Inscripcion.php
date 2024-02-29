<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\tt_t_usuario as Users;

class tt_t_Inscripcion extends Model
{
    use HasFactory;
    protected $table = "tt_t_inscripcion";

    protected $fillable = [
        'id_user_cliente',
        'id_user_entrenador',
        'fecha_inicio',
        'peso_maximo',
        'estado'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function cliente() {
        return $this->hasOne(Users::class, 'id', 'id_user_cliente');
    }

    public function entrenador() {
        return $this->hasOne(Users::class, 'id', 'id_user_entrenador');
    }
}
