Hello {{$user->name}},

We need you to verify your new email address. Please, verify your email using this link:
{{route('users.verify', $user->verification_token)}}