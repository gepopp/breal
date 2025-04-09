@php
    $classes = "font-extrabold align-baseline tracking-wide -mb-px ";
    $classes .= "text-5xl lg:text-6xl xl:text-7xl ";
    $classes .= "dark:text-white ";
    $classes .= "leading-[45px] lg:leading-[56px] xl:leading-[65px] ";
@endphp

<h3 {{ $attributes->merge( ['class' => $classes] ) }}>
    <span class="bg-logo text-white inline-block -my-1 px-2 rounded">be</span>
    {{ $slot }}
</h3>