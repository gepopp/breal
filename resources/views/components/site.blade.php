<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-white dark:bg-logo-950 dark:text-white text-logo-950 min-h-screen">
<livewire:parts.navbar/>

<div class="overflow-hidden">
    <main data-aos="fade-up" data-aos-delay="1000">
        {{ $slot }}
    </main>
</div>
<livewire:footer/>
@fluxScripts
</body>
</html>