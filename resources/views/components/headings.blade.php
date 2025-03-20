@props([
    'level' => null,
    'tag' => 'real'
])

@php
    $classes = 'mt-2 text-xl font-bold text-logo dark:text-white';
@endphp

<div class="tagged-heading mb-8">
    <div class="tagline flex items-center" data-aos="fade">
        <span class="-mb-1.5">{{$tag}}</span>
    </div>
    <?php switch ((int)$level): case(1): ?>
    <h1 data-aos="fade" data-aos-delay="500" {{ $attributes->class($classes) }}>{{ $slot }}</h1>

    @break
    <?php case(2): ?>
    <h2 {{ $attributes->class($classes) }} data-flux-heading>{{ $slot }}</h2>

    @break
    <?php case(3): ?>
    <h3 {{ $attributes->class($classes) }}>{{ $slot }}</h3>

    @break
    <?php case(4): ?>
    <h4 {{ $attributes->class($classes) }}>{{ $slot }}</h4>

    @break
    <?php default: ?>
    <div {{ $attributes->class($classes) }}>{{ $slot }}</div>
    <?php endswitch; ?>


</div>

