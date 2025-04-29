@php
    use Illuminate\Support\Facades\Route;
@endphp
@props([
    'level' => null,
    'tag' => 'real',
    'ondark' => false
])

@php
    $classes = 'text-xl font-bold dark:text-white';
@endphp

<div @class([
        "tagged-heading mb-4",
        "!text-white" => $ondark,
        "text-logo-500" => !Route::is('technik.*', 'immobilien.*') && !$ondark,
        "text-technik-500" => Route::is('technik.*') && !$ondark,
        "text-makler-500" => Route::is('immobilien.*') && !$ondark,
])>
    <div @class([
        "flex items-center font-logo text-3xl md:text-4xl lg:text-5xl xl:text-7xl font-extrabold rounded tracking-wide lowercase",
        "before:content-['be'] before:h-[32px] before:mb-[6px] before:md:h-[36px] before:lg:h-[42px] before:xl:h-[62px] before:align-baseline before:rounded before:px-2 before:tracking-wide dark:before:bg-white before:bg-logo dark:before:text-logo before:mr-2",
        "text-logo-500" => !Route::is('technik.*', 'immobilien.*') && !$ondark,
        "text-technik-500" => Route::is('technik.*') && !$ondark,
        "text-makler-500" => Route::is('immobilien.*') && !$ondark,
        "before:text-logo-500" => !Route::is('technik.*', 'immobilien.*') && $ondark,
        "before:text-technik-500" => Route::is('technik.*') && $ondark,
        "before:text-makler-500 dark:before:text-makler-950" => Route::is('immobilien.*') && $ondark,
        "before:bg-white " => $ondark,
        "before:text-white before:bg-logo" => !$ondark
        ])
         data-aos="fade" data-aos-once="true">
        <span class="font-logo dark:text-white">{{$tag}}</span>
    </div>
    <?php switch ((int)$level): case(1): ?>
    <h1 data-aos="fade" data-aos-once="true" data-aos-delay="750" {{ $attributes->class($classes) }}>{{ $slot }}</h1>

    @break
    <?php case(2): ?>
    <h2 {{ $attributes->class($classes) }} data-aos-once="true" data-aos="fade" data-aos-delay="500" data-flux-heading>{{ $slot }}</h2>

    @break
    <?php case(3): ?>
    <h3 {{ $attributes->class($classes) }} data-aos-once="true" data-aos="fade" data-aos-delay="500">{{ $slot }}</h3>

    @break
    <?php case(4): ?>
    <h4 {{ $attributes->class($classes) }} data-aos-once="true" data-aos="fade" data-aos-delay="500">{{ $slot }}</h4>

    @break
    <?php default: ?>
    <div {{ $attributes->class($classes) }} data-aos-once="true" data-aos="fade" data-aos-delay="500">{{ $slot }}</div>
    <?php endswitch; ?>
</div>

