<x-mail::message>
# Eine neue Kontaktanfrage wurde soeben bestätigt.

{{ $contactRequest->subject }}

{{ $contactRequest->firstname }} {{ $contactRequest->lastname }}

[{{ $contactRequest->email }}](mailto:{{ $contactRequest->email }})

[{{ $contactRequest->phone }}](tel:{{ $contactRequest->phone }})


{!! $contactRequest->message !!}

</x-mail::message>
