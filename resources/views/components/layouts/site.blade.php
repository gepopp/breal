@props(['title' => null, 'description' => null, 'canonical' => null, 'noindex' => false, 'schema' => []])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'de' ? 'de-AT' : str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @fluxAppearance
    @cookieconsentscripts
    @include('partials.head')
</head>
<!-- Event snippet for Seitenaufruf conversion page -->
<script>
    if (typeof gtag === 'function') {
        gtag('event', 'conversion', {
            'send_to': 'AW-17344076990/lKL_CLe4mvEaEL65ps5A',
            'value': 1.0,
            'currency': 'EUR'
        });
    }
</script>
<body class="bg-white dark:bg-logo-950 dark:text-white text-logo-950 min-h-screen">

<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[10000] focus:bg-white focus:text-logo-950 focus:px-4 focus:py-2 focus:rounded focus:shadow-lg">{{ __('navigation.skip_to_content') }}</a>
<div class="overflow-hidden flex flex-col min-h-screen">
    <livewire:parts.navbar/>
    <main id="main" tabindex="-1" class="z-[8888] flex-1 focus:outline-none">
        {{ $slot }}
    </main>
    <livewire:footer/>
</div>

@fluxScripts
@include('components.dev-switsher')
@cookieconsentview
</body>
</html>