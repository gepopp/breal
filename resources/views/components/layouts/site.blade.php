<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
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

<div class="overflow-hidden">
    <livewire:parts.navbar/>
    <main class="z-[8888]">
        {{ $slot }}
    </main>
    <livewire:footer/>
</div>

@fluxScripts
@include('components.dev-switsher')
@cookieconsentview
</body>
</html>