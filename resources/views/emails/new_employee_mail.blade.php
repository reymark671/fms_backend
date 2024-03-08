<x-mail::message>
# Blessed FMS Account Creation

You're account was successfully added.
You may now log-in to our portal using your control number and password.

# Control Number: {{ $controlnumber }}
# Password: {{ $password }}

<x-mail::button :url="$login_url">
Log In Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
