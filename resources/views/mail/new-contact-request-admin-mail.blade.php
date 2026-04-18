<x-mail::message>
# Eine neue Kontaktanfrage wurde soeben bestätigt.

{{ $contactRequest->subject }}

{{ $contactRequest->firstname }} {{ $contactRequest->lastname }}

[{{ $contactRequest->email }}](mailto:{{ $contactRequest->email }})

[{{ $contactRequest->phone }}](tel:{{ $contactRequest->phone }})

@if($contactRequest->address)
{{ $contactRequest->address }}
@endif

{!! $contactRequest->message !!}

@php($uploads = $contactRequest->getMedia('uploads'))
@if($uploads->isNotEmpty())
**Anhänge:**

@foreach($uploads as $upload)
- [{{ $upload->file_name }}]({{ $upload->getTemporaryUrl(now()->addDays(7)) }})
@endforeach
@endif

</x-mail::message>
