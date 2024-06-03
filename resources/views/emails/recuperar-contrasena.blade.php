@component('mail::message')
# Recuperación de Contraseña

Hola {{ $user->name }},

Has solicitado restablecer tu contraseña en nuestra aplicación. Haz clic en el siguiente botón para cambiar tu contraseña:

@component('mail::button', ['url' => config('app.client_url').'/reset-password/'.$user->recuperar_token])
Cambiar Contraseña
@endcomponent

Si no solicitaste esta acción, no es necesario realizar ninguna acción adicional.

¡Gracias!<br>
{{ config('app.name') }}
@endcomponent 
