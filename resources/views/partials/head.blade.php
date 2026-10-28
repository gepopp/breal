<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{ $description ?? 'Wir sind Ihr verlässlicher Partner in Wien und ganz Österreich, wenn es um die ganzheitliche Abwicklung und Betreuung Ihrer Immobilien geht.'}}"/>
<title>{{ $title ?? 'bereal' }}</title>
<meta name="keywords" content="HTML, CSS, JavaScript">
<meta name="description" content="{{ $description ?? '' }}">
@php($localeUrls = app(\App\Services\LocaleUrls::class))
<link rel="canonical" href="{{ $canonical ?? $localeUrls->current() }}">
@if($localeUrls->isLocalized())
    @foreach($localeUrls->alternates() as $hreflang => $alternateUrl)
        <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $alternateUrl }}">
    @endforeach
@endif
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

