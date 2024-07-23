<x-mail::message>
## Dear Concern,

Massage received from {{ url('/') }}.
@if(isset($name))
**Name**: {{ $name }}<br>
@else
**Name**: {{ $firstName }} {{ $lastName }}<br>
@endif
@if(isset($business_name))
**Business Name**: {{ $business_name }}<br>
@endif
@if($phone)
**Phone**: {{ $phone }}<br>
@endif
@if($email)
**Email**: {{ $email }}<br>
@endif
@if(isset($message))
**Message**: {{ $message }}<br>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
