<x-mail::message>
# You're invited!

Hello, {{ $invitation->name }}!

You have been invited to join {{ config('app.name') }}.

<x-mail::button :url="$url">
Accept Invitation
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
