@component('mail::message')
# Application update

Dear {{ $client->first_name }},

Your application status has been updated to Active.

Thank you for your continued engagement.

Best regards,<br>
Your Application Team
@endcomponent
