<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use \App\Models\tt_t_usuario as Usuario;
use App\Models\tt_t_inscripcion as Inscripcion;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Validator::extend('es_cliente', function ($attribute, $value, $parameters, $validator) {
            return Usuario::where('id', $value)->where('id_rol', 1)->exists();
        });

        Validator::extend('es_entrenador', function ($attribute, $value, $parameters, $validator) {
            return Usuario::where('id', $value)->where('id_rol', 2)->exists();
        });

        Validator::extend('youtube_url', function($attribute, $value, $parameters, $validator) {
            $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)[\w-]{11}(?:\?[^\s]*)?$/';
            return preg_match($pattern, $value);
        });

        Validator::extend('inscripcion_activa', function ($attribute, $value, $parameters, $validator) {
            return Inscripcion::where('id', $value)->where('estado', 1)->exists();
        });
    }
}
