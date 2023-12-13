<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $table = 'TT_T_Rol';

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
