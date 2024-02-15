<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\tt_t_rol as Rol;

class tt_t_usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'tt_t_usuario';
    
    protected $fillable = [
        'name',
        'firstSurname',
        'secondSurname',
        'telephone',
        'email',
        'password',
        'id_rol',
        'recuperar_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'id_rol',
        'recuperar_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
