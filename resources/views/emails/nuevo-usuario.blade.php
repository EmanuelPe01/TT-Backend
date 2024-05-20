@component('mail::message')
# Accede a tu Box Virtual de {{ config('app.name') }}

Hola {{ $user->name }},

¡Bienvenido al Box virtual de {{ config('app.name') }}! <br>
Te hemos registrado exitosamente como <strong>{{ $role }}</strong> y generamos una contraseña para que puedas acceder. 
Te recomendamos cambiar esta contraseña al iniciar sesión por primera vez.

Contraseña: <strong>{{$pass}}</strong>

@component('mail::button', ['url' => 'http://localhost:4200/'])
Iniciar sesión
@endcomponent

¡Bienvenido!<br>
{{ config('app.name') }}
@endcomponent 