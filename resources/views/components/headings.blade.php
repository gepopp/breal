@php
    use Illuminate\Support\Facades\Route;
@endphp
@props([
    'level' => null,
    'tag' => 'real',
    'ondark' => false,
    'animate' => true,
    'tagIsHeading' => false,
])

@php
    $classes = 'text-xl font-bold dark:text-white';

    // The big tagged line ("be welcome") is the page heading itself; the text below it is only a lead paragraph.
    $tagElement = $tagIsHeading ? 'h1' : 'div';
    $level = $tagIsHeading ? null : $level;

    // As the h1 the line carries a real, hidden "be " for search engines and screen readers; the drawn "be" is then
    // marked as decorative (empty alternative text) so that it is not read twice.
    $beContent = $tagIsHeading ? "before:content-['be'_/_'']" : "before:content-['be']";
@endphp

<div @class([
        "tagged-heading mb-4",
        "!text-white" => $ondark,
        "text-logo-500" => !Route::is('technik.*', 'makler.*') && !$ondark,
        "text-technik-700 dark:text-technik-500" => Route::is('technik.*') && !$ondark,
        "text-makler-500" => Route::is('makler.*') && !$ondark,
])>
    @if($animate)
        <{{ $tagElement }} @class([
        "flex items-center font-logo text-3xl md:text-4xl lg:text-5xl xl:text-7xl font-extrabold rounded tracking-wide lowercase",
        $beContent.' before:h-[32px] before:mb-[6px] before:md:h-[36px] before:lg:h-[42px] before:xl:h-[62px] before:align-baseline before:rounded before:px-2 before:tracking-wide dark:before:bg-white before:bg-logo dark:before:text-logo before:mr-2',
        "text-logo-500" => !Route::is('technik.*', 'makler.*') && !$ondark,
        "text-technik-700 dark:text-technik-500" => Route::is('technik.*') && !$ondark,
        "text-makler-500" => Route::is('makler.*') && !$ondark,
        "before:text-logo-500" => !Route::is('technik.*', 'makler.*') && $ondark,
        "before:text-technik-500" => Route::is('technik.*') && $ondark,
        "before:text-makler-500 dark:before:text-makler-950" => Route::is('makler.*') && $ondark,
        "before:bg-white " => $ondark,
        "before:text-white before:bg-logo" => !$ondark
        ])
             data-aos="fade" data-aos-once="true">
            @else
                <{{ $tagElement }} @class([
        "flex items-center font-logo text-3xl md:text-4xl lg:text-5xl xl:text-7xl font-extrabold rounded tracking-wide lowercase",
        $beContent.' before:h-[32px] before:mb-[6px] before:md:h-[36px] before:lg:h-[42px] before:xl:h-[62px] before:align-baseline before:rounded before:px-2 before:tracking-wide dark:before:bg-white before:bg-logo dark:before:text-logo before:mr-2',
        "text-logo-500" => !Route::is('technik.*', 'makler.*') && !$ondark,
        "text-technik-700 dark:text-technik-500" => Route::is('technik.*') && !$ondark,
        "text-makler-500" => Route::is('makler.*') && !$ondark,
        "before:text-logo-500" => !Route::is('technik.*', 'makler.*') && $ondark,
        "before:text-technik-500" => Route::is('technik.*') && $ondark,
        "before:text-makler-500 dark:before:text-makler-950" => Route::is('makler.*') && $ondark,
        "before:bg-white " => $ondark,
        "before:text-white before:bg-logo" => !$ondark
        ])>
                    @endif
                    @if($tagIsHeading)<span class="sr-only">be </span>@endif
                    <span class="font-logo dark:text-white">{{$tag}}</span>
                </{{ $tagElement }}>
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
            @if($animate)
                    <div {{ $attributes->class($classes) }} data-aos-once="true" data-aos="fade" data-aos-delay="500">{{ $slot }}</div>

            @else
                    <div {{ $attributes->class($classes) }}>{{ $slot }}</div>

            @endif

                <?php endswitch; ?>
        </div>

