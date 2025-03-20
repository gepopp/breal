<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Wir sind Ihr verlässlicher Partner in Wien und ganz Österreich, wenn es um die ganzheitliche Abwicklung und Betreuung Ihrer Immobilien geht."/>
    <title>{{ $title ?? 'Bontus Eybel' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <!-- Fonts -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @fluxAppearance
</head>
<body class="bg-white dark:bg-logo-950 dark:text-white text-logo-950 min-h-screen">
<livewire:navbar/>
{{ $slot }}
<livewire:footer/>
@fluxScripts
</body>
</html>