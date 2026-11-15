@php
    use Illuminate\Support\Facades\Route;
@endphp
<header class="relative z-50" id="navbar">
    <livewire:parts.preheader/>
    <div x-data="navControl">
        <div @class([
        "relative",
        "border-b-2 shadow flex justify-between items-end text-white p-4",
        "border-logo-900 dark:border-logo-50" => !Route::is('makler.*', 'technik.*'),
        "border-technik-900 dark:border-technik-50" => Route::is('technik.*'),
        "border-makler-900 dark:border-makler-50" => Route::is('makler.*'),
])>
            <div class="flex items-center">
                @if( Route::is('hausverwaltung.*') )
                    <a href="{{ route('hausverwaltung.home') }}">
                        <img src="{{ asset('logos/bereal_immobilien.svg') }}" alt="be real Immobilien" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_immobilien_white.svg') }}" alt="be real Immobilien"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>

                @elseif( Route::is('makler.*') )
                    <a href="{{ route('makler.home') }}">
                        <img src="{{ asset('logos/bereal_makler.svg') }}" alt="be real Makler" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_makler_white.svg') }}" alt="be real Makler"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>

                @elseif( Route::is('technik.*') )
                    <a href="{{ route('technik.home') }}">
                        <img src="{{ asset('logos/bereal_technik.svg') }}" alt="be real Technik" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_technik_white.svg') }}" alt="be real Technik"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>

                @else
                    <a href="{{ route('hausverwaltung.home') }}">
                        <img src="{{ asset('logos/bereal.svg') }}" alt="be real" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_white.svg') }}" alt="be real"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>
                @endif
            </div>


            @php($navigation = \App\Services\MainNavigation::forCurrentPage())
            <nav aria-label="{{ __('navigation.main') }}" class="hidden lg:flex mb-px space-x-12 justify-between items-center font-bold text-logo-500 dark:text-white">
                @foreach(collect($navigation['items'])->where('desktop') as $item)
                    @if($item['dropdown'])
                        <div x-data="{ show:false }" x-on:mouseenter="show = true" x-on:mouseleave="show = false" class="relative">
                            <div class="overflow-hidden">
                                <a href="{{ route($item['route']) }}"
                                   class="menu-item relative uppercase cursor-pointer text-logo-600">
                                    <div @class(["menu-item-active" => Route::is($item['active'])])>
                                        <span class="menu-item-text pointer-events-none block relative">{{ $item['label'] }}</span>
                                    </div>
                                </a>
                            </div>
                            <div x-show="show" x-cloak x-transition x-on:mouseleave="show ? show = false : null" class="transition-all duration-500">
                                @livewire($item['dropdown'])
                            </div>
                        </div>
                    @else
                        <div class="relative">
                            <div class="overflow-hidden">
                                <a href="{{ route($item['route']) }}"
                                   class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                    <div @class(["menu-item-active" => Route::is($item['active'])])>
                                        <span class="menu-item-text pointer-events-none block relative">{{ $item['label'] }}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>


            <div class="lg:hidden">
                <button type="button" x-on:click="open = !open" x-on:keydown.escape.window="open = false"
                        x-bind:aria-expanded="open.toString()" aria-controls="mobile-menu"
                        aria-label="{{ __('navigation.menu') }}"
                        class="rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-logo-500">
                <svg aria-hidden="true" class="size-6 text-logo dark:text-white"
                     xmlns="http://www.w3.org/2000/svg" width="28" height="15" viewBox="0 0 28 15">
                    <line :class="open ? 'hidden' : ''" class="transition-all duration-300 ease-in-out" x2="20"
                          transform="translate(6.5 1.5)" fill="none" stroke="currentColor" stroke-linecap="round"
                          stroke-width="3"/>
                    <line :class="open ? 'rotate-45 origin-center scale-x-110' : ''"
                          class="transition-all duration-300 ease-in-out" x2="25" transform="translate(1.5 7.5)"
                          fill="none"
                          stroke="currentColor" stroke-linecap="round" stroke-width="3"/>
                    <line :class="open ? '-rotate-45 origin-center scale-x-110' : ''"
                          class="transition-all duration-300 ease-in-out" x2="25" transform="translate(1.5 7.5)"
                          fill="none"
                          stroke="currentColor" stroke-linecap="round" stroke-width="3"/>
                    <line :class="open ? 'hidden' : ''" x2="18" class="transition-all duration-300 ease-in-out"
                          transform="translate(8.5 13.5)" fill="none" stroke="currentColor" stroke-linecap="round"
                          stroke-width="3"/>
                </svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" x-show="open" x-collapse.duration.500ms x-cloak
             class="absolute top-full w-full shadow-xl lg:hidden bg-white z-[9999]">
            <div @class([
        "border-b-2 shadow lg:max-w-4xl xl:max-w-6xl mx-auto flex justify-between items-center p-4 w-full",
        "bg-white dark:bg-logo-950 border-logo-900 dark:border-logo-50 text-logo-950 dark:text-logo-50 dark:border-logo-50" => Route::is('hausverwaltung.*'),
        "bg-white dark:bg-technik-950 border-technik-900 dark:border-technik-50 text-technik-950 dark:text-technik-50 dark:border-technik-50" => Route::is('technik.*'),
        "bg-white dark:bg-makler-950 border-makler-900 dark:border-makler-50 text-makler-950 dark:text-makler-50 dark:border-makler-50" => Route::is('makler.*'),
])>
                <ul class="flex flex-col space-y-8 text-lg font-bold uppercase">
                    @if( Route::is('hausverwaltung.*', 'verwalterservice', 'makler.*', 'technik.*') )
                        @foreach(collect($navigation['items'])->where('mobile') as $item)
                            <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div @class([
        "grid grid-cols-2 w-full border-b-2 bg-white",
        "border-logo-900 dark:border-logo-50 dark:bg-logo-950" => Route::is('hausverwaltung.*'),
        "border-technik-900 dark:border-technik-50 dark:bg-technik-950" => Route::is('technik.*'),
        "border-makler-900 dark:border-makler-50 dark:bg-makler-950" => Route::is('makler.*'),
])>
                @if( ! Route::is('hausverwaltung.*') )
                    <a href="{{ route('hausverwaltung.home') }}" class="aspect-square flex items-center justify-center"
                    >
                        <img src="{{ asset('logos/bereal_immobilien_vertical.svg') }}" alt="be real Immobilien" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_immobilien_vertical_white.svg') }}" alt="be real Immobilien"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif

                @if( ! Route::is('makler.*') )
                    <a href="{{ route('makler.home') }}"
                       class="aspect-square sm:aspect-[4/3] flex items-center justify-center"
                    >
                        <img src="{{ asset('logos/bereal_makler_vertical.svg') }}" alt="be real Makler" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_makler_vertical_white.svg') }}" alt="be real Makler"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif


                @if( ! Route::is('technik.*') )
                    <a href="{{ route('technik.home') }}"
                       class="aspect-square sm:aspect-[4/3] flex items-center justify-center"
                    >
                        <img src="{{ asset('logos/bereal_technik_vertical.svg') }}" alt="be real Technik" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_technik_vertical_white.svg') }}" alt="be real Technik"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif
            </div>


            <div @class([ "py-2 px-4 flex justify-between",
             "bg-logo-900 dark:bg-logo-950" => Route::is('hausverwaltung.*'),
             "bg-technik-900 dark:bg-technik-950" => Route::is('technik.*'),
             "bg-makler-900 dark:bg-makler-950" => Route::is('makler.*'),
             ])>
                <div>
                    <img src="{{ asset('logos/bereal_immobilien_icon_white.svg') }}" alt="be real Immobilien" class="h-5">
                </div>
                <div class="flex items-center space-x-2 text-white">
                    <a href="https://realonline.bereal-immobilien.at/" class="font-bold uppercase"
                       target="_blank">{{ __('navigation.customer_login') }}</a>

                    <div class="w-2 h-2 bg-white rounded last:hidden"></div>

                        <div>
                            @if(app()->getLocale() == 'de')
                                <a href="{{ app(\App\Services\LocaleUrls::class)->forLocale('en') }}" class="font-bold uppercase" data-locale-switch>ENGLISH</a>
                            @else
                                <a href="{{ app(\App\Services\LocaleUrls::class)->forLocale('de') }}" class="font-bold uppercase" data-locale-switch>DEUTSCH</a>
                            @endif
                        </div>

                </div>
            </div>

        </div>
    </div>
</header>
@script
<script>
    Alpine.data('navControl', () => {
        return {
            open: false
        }
    })


    const menuItems = [...document.querySelectorAll(".menu-item")];

    menuItems.forEach(item => {
        let word = item.children[0].children[0].innerText.split('');
        item.setAttribute('aria-label', item.children[0].children[0].innerText.trim());
        item.children[0].innerHTML = '';
        word.forEach((letter, idx) => {
            item.children[0].innerHTML += `<span style="--index: ${idx};">${letter}</span>`;
        })
        item.children[0].setAttribute('aria-hidden', 'true');
        let cloneDiv = item.children[0].cloneNode(true);
        cloneDiv.style.position = "absolute";
        cloneDiv.style.top = "0px";
        cloneDiv.style.left = "0px";
        item.appendChild(cloneDiv);
    })
</script>
@endscript