<x-mail::message>
# Blessed FMS Account Alert.

Your password has been successfully reset.
You may now login to your account using your new password.
here is your new password 

# {{ $pword }}

<x-mail::button :url="$url">
Account Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
