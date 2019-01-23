Hello {!! $user->name !!} {!! $user->lastname !!},

Welcome!

Your BK Foundations account has been created. Please click on the link below and enter the following account information to login:

{!! url('auth/login') !!}

Email: {!! $user->email !!}
Password: {!! $user->text_password !!}