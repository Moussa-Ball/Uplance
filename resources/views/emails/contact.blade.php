@component('mail::message')
## New message from {{ $email->name }}

{{ $email->content }}

@endcomponent
