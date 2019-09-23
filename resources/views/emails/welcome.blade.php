Hello {{$user->name}},

Thank you for creating an account with us. Please, verify your email using this link:
{{route('users.verify', $user->verification_token)}}