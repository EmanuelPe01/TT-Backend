<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tt_t_rol extends Model
{
    use HasFactory;
    protected $table = 'tt_t_rol';

    protected $fillable = [
        'rol_name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function Usuarios() {
        return $this->hasMany(User::class, 'id_user');
    }
}
