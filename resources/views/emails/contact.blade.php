<x-mail::message>
## Dear Concern,

Massage received from {{ url('/') }}.

**Name**: {{ $firstName }} {{ $lastName }}<br>
@if($phone)
**Phone**: {{ $phone }}<br>
@endif
@if($email)
**Email**: {{ $email }}<br>
@endif
**Message**: {{ $message }}<br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
