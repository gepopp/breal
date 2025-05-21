<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<!-- Google tag (gtag.js) -->
{{--<script async src="https://www.googletagmanager.com/gtag/js?id=G-H3QQKE1XTQ"></script>--}}
{{--<script> window.dataLayer = window.dataLayer || [];--}}

{{--    function gtag() {--}}
{{--        dataLayer.push(arguments);--}}
{{--    }--}}

{{--    gtag('js', new Date());--}}
{{--    gtag('config', 'G-H3QQKE1XTQ'); </script>--}}
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