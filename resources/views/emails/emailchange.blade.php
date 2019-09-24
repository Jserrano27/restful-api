@component('mail::message')
# Hello {{$user->name}}

We need you to verify your new email address. Please, verify your email using this button:

@component('mail::button', ['url' => route('users.verify', $user->verification_token)])
Verify Email
@endcomponent

You can additionally verify your email using this link:
{{route('users.verify', $user->verification_token)}}

Thanks!,<br>
{{ config('app.name') }}
@endcomponent