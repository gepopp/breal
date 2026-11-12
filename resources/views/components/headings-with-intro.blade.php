@props([
        'ondark' => false,
        'header',
        'subheader'
])
<div @class([
    "md:max-w-sm lg:max-w-lg",
    "text-logo-500" => !Route::is('technik.*', 'makler.*'),
    "text-technik-500" => Route::is('technik.*'),
    "text-makler-500" => Route::is('makler.*'),
])>
    <x-tagline>{{ $header }}</x-tagline>
    <x-subheading>{{ $subheader }}</x-subheading>
    <div class="prose">
        {!! $slot !!}
    </div>
</div>