<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
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
</body>
</html>