<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use \App\Models\tt_t_usuario as Usuario;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('es_cliente', function ($attribute, $value, $parameters, $validator) {
            return Usuario::where('id', $value)->where('id_rol', 1)->exists();
        });

        Validator::extend('es_entrenador', function ($attribute, $value, $parameters, $validator) {
            return Usuario::where('id', $value)->where('id_rol', 2)->exists();
        });
    }
}
