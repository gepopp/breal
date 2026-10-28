@php
    $localeUrls = app(\App\Services\LocaleUrls::class);
    $metaTitle = $title ?? \App\Services\PageMeta::title('default');
    $metaDescription = $description ?? \App\Services\PageMeta::description('default');
    $metaUrl = $canonical ?? $localeUrls->current();
    $metaImage = asset(\App\Services\PageMeta::socialImage());
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
@if($noindex ?? false)
    <meta name="robots" content="noindex">
@endif
<link rel="canonical" href="{{ $metaUrl }}">
@if($localeUrls->isLocalized())
    @foreach($localeUrls->alternates() as $hreflang => $alternateUrl)
        <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $alternateUrl }}">
    @endforeach
@endif
<link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('sitemap.xml') }}">
<meta property="og:site_name" content="be real Immobilien">
<meta property="og:type" content="website">
<meta property="og:locale" content="{{ app()->getLocale() === 'de' ? 'de_AT' : 'en_GB' }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $metaUrl }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ \App\Services\PageMeta::socialImageAlt() }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">
<meta name="theme-color" content="#002540">
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
@if(! empty($schema))
    {!! \App\Services\StructuredData::script($schema) !!}
@endif
