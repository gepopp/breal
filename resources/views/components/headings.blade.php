@php
    use Illuminate\Support\Facades\Route;
@endphp
@props([
    'level' => null,
    'tag' => 'real',
    'ondark' => false
])

@php
    $classes = 'text-xl font-logo font-bold dark:text-white';
@endphp

<div @class([
        "tagged-heading mb-4 font-logo",
        "!text-white" => $ondark,
        "text-logo-500" => !Route::is('technik.*', 'immobilien.*') && !$ondark,
        "text-technik-500" => Route::is('technik.*') && !$ondark,
        "text-makler-500" => Route::is('immobilien.*') && !$ondark,
])>
    <div @class([
        "flex items-center font-logo",
        "text-logo-500" => !Route::is('technik.*', 'immobilien.*') && !$ondark,
        "text-technik-500" => Route::is('technik.*') && !$ondark,
        "text-makler-500" => Route::is('immobilien.*') && !$ondark,
        "!text-white tagline-ondark" => $ondark,
        "tagline" => !$ondark
        ])
         data-aos="fade">
        <span class="font-logo">{{$tag}}</span>
    </div>
    <?php switch ((int)$level): case(1): ?>
    <h1 data-aos="fade" data-aos-delay="750" {{ $attributes->class($classes) }}>{{ $slot }}</h1>

    @break
    <?php case(2): ?>
    <h2 {{ $attributes->class($classes) }} data-aos="fade" data-aos-delay="500" data-flux-heading>{{ $slot }}</h2>

    @break
    <?php case(3): ?>
    <h3 {{ $attributes->class($classes) }} data-aos="fade" data-aos-delay="500">{{ $slot }}</h3>

    @break
    <?php case(4): ?>
    <h4 {{ $attributes->class($classes) }} data-aos="fade" data-aos-delay="500">{{ $slot }}</h4>

    @break
    <?php default: ?>
    <div {{ $attributes->class($classes) }} data-aos="fade" data-aos-delay="500">{{ $slot }}</div>
    <?php endswitch; ?>
</div>

