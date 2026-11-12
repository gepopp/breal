@props(['title' => null, 'description' => null, 'canonical' => null, 'noindex' => false, 'schema' => []])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'de' ? 'de-AT' : str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @fluxAppearance
    @cookieconsentscripts
    @include('partials.head')
    {{-- The two fonts of the first screen (logo font of the headings, body text): found by the browser from the head
         instead of only after it has read the stylesheet. --}}
    @foreach(['resources/fonts/EmBauhaus-W00-Bold.woff2', 'node_modules/@fontsource/poppins/files/poppins-latin-400-normal.woff2'] as $font)
        <link rel="preload" as="font" type="font/woff2" href="{{ \Illuminate\Support\Facades\Vite::asset($font) }}" crossorigin>
    @endforeach
    @foreach(app(\App\Services\PreloadedImages::class)->all() as $image)
        <link rel="preload" as="image" href="{{ $image['src'] }}" @if($image['srcset']) imagesrcset="{{ $image['srcset'] }}" imagesizes="{{ $image['sizes'] }}" @endif fetchpriority="high">
    @endforeach
    {{-- Livewire only injects its assets into responses with status 200; included here so the 404 page
         (served with status 404) also gets Alpine and the x-cloak style, otherwise the open menus show. --}}
    @livewireStyles
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
<script>
    // Swiper comes with the bundled app script, which runs after the page has been parsed:
    // slider scripts in the page wait for it.
    window.onSwiper = function (callback) {
        if (window.Swiper) {
            callback();
        } else {
            document.addEventListener('DOMContentLoaded', callback, { once: true });
        }
    };
</script>
<body class="bg-white dark:bg-logo-950 dark:text-white text-logo-950 min-h-screen" data-carousel-pause="{{ __('carousel.pause') }}" data-carousel-play="{{ __('carousel.play') }}" data-carousel-a11y="{{ json_encode(['prevSlideMessage' => __('carousel.previous'), 'nextSlideMessage' => __('carousel.next'), 'firstSlideMessage' => __('carousel.first'), 'lastSlideMessage' => __('carousel.last'), 'paginationBulletMessage' => __('carousel.bullet'), 'slideLabelMessage' => __('carousel.slide_label')], JSON_UNESCAPED_UNICODE) }}">

<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[10000] focus:bg-white focus:text-logo-950 focus:px-4 focus:py-2 focus:rounded focus:shadow-lg">{{ __('navigation.skip_to_content') }}</a>
<div class="overflow-hidden flex flex-col min-h-screen">
    <livewire:parts.navbar/>
    {{-- z-0 keeps the page content (decorative z-[9999] shapes) in its own stacking context below the header (z-50), so the menu dropdowns are never covered. --}}
    <main id="main" tabindex="-1" class="z-0 flex-1 focus:outline-none">
        {{ $slot }}
    </main>
    <livewire:footer/>
</div>

@fluxScripts
@include('components.dev-switsher')
@cookieconsentview
@livewireScripts
</body>
</html>