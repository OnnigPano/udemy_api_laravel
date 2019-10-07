@component('mail::message')
# Hola {{$user->name}}

Has cambiado tu correo electrónico, por favor verifica la nueva dirección usando el siguiente botón.

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Confirmar
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
