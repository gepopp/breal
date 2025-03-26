<div class="relative">
    <livewire:parts.preheader/>
    <div x-data="navControl">
        <div @class([
        "relative",
        "border-b-2 shadow flex justify-between items-end text-white p-4",
        "border-logo-900 dark:border-logo-50" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "border-technik-900 dark:border-technik-50" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "border-makler-900 dark:border-makler-50" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
])>
            <div class="flex items-center">
                @if( \Illuminate\Support\Facades\Route::is('hausverwaltung.*') )
                    <a href="{{ route('hausverwaltung.home') }}" wire:navigate>
                        <img src="{{ asset('logos/bereal_immobilien.svg') }}" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_immobilien_white.svg') }}" class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>
                @endif

                @if( \Illuminate\Support\Facades\Route::is('immobilien.*') )
                    <a href="{{ route('immobilien.home') }}" wire:navigate>
                        <img src="{{ asset('logos/bereal_makler.svg') }}" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_makler_white.svg') }}" class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>
                @endif


                @if( \Illuminate\Support\Facades\Route::is('technik.*') )
                    <a href="{{ route('technik.home') }}" wire:navigate>
                        <img src="{{ asset('logos/bereal_technik.svg') }}" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_technik_white.svg') }}" class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>
                @endif
            </div>



            <nav class="hidden lg:flex mb-px space-x-12 justify-between items-center overflow-hidden font-bold text-logo-500 dark:text-white">
                @if( \Illuminate\Support\Facades\Route::is('hausverwaltung.*') )
                    <a href="{{ route('hausverwaltung.leistungen') }}" class="menu-item relative uppercase cursor-pointer text-logo-600">
                        <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('hausverwaltung.leistungen')])>
                            <span class="menu-item-text pointer-events-none block relative">Leistungen</span>
                        </div>
                    </a>
                    <a href="{{ route('hausverwaltung.service') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                        <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('hausverwaltung.service')])>
                            <span class="menu-item-text pointer-events-none block relative">Service</span>
                        </div>
                    </a>
                    <a href="{{ route('hausverwaltung.karriere') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                        <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('hausverwaltung.karriere')])>
                            <span class="menu-item-text pointer-events-none block relative">Karriere</span>
                        </div>
                    </a>
                    <a href="{{ route('hausverwaltung.kontakt') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                        <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('hausverwaltung.kontakt')])>
                            <span class="menu-item-text pointer-events-none block relative">Kontakt</span>
                        </div>
                    </a>
                @endif

                @if( \Illuminate\Support\Facades\Route::is('immobilien.*') )
                        <a href="{{ route('immobilien.immobiliensuche') }}" class="menu-item relative uppercase cursor-pointer text-logo-600">
                            <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('immobilien.immobiliensuche')])>
                                <span class="menu-item-text pointer-events-none block relative">Immobiliensuche</span>
                            </div>
                        </a>
                        <a href="{{ route('immobilien.ueber-uns') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                            <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('immobilien.ueber-uns')])>
                                <span class="menu-item-text pointer-events-none block relative">Über uns</span>
                            </div>
                        </a>
                        <a href="{{ route('immobilien.karriere') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                            <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('immobilien.karriere')])>
                                <span class="menu-item-text pointer-events-none block relative">Karriere</span>
                            </div>
                        </a>
                        <a href="{{ route('immobilien.kontakt') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                            <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('immobilien.kontakt')])>
                                <span class="menu-item-text pointer-events-none block relative">Kontakt</span>
                            </div>
                        </a>
                @endif


                @if( \Illuminate\Support\Facades\Route::is('technik.*') )
                        <a href="{{ route('technik.karriere') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                            <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('technik.karriere')])>
                                <span class="menu-item-text pointer-events-none block relative">Karriere</span>
                            </div>
                        </a>
                        <a href="{{ route('technik.kontakt') }}" class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                            <div @class(["font-black" => \Illuminate\Support\Facades\Route::is('technik.kontakt')])>
                                <span class="menu-item-text pointer-events-none block relative">Kontakt</span>
                            </div>
                        </a>
                @endif
            </nav>



            <div class="lg:hidden">
                <svg x-on:click="open = !open" class="size-6 text-logo dark:text-white"
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
            </div>
        </div>
        <div x-show="open" x-collapse.duration.500ms x-cloak class="absolute top-full w-full shadow-xl lg:hidden">
            <div @class([
        "border-b-2 shadow lg:max-w-4xl xl:max-w-6xl mx-auto flex justify-between items-center p-4 w-full",
        "bg-white dark:bg-logo-950 border-logo-900 dark:border-logo-50 text-logo-950 dark:text-logo-50 dark:border-logo-50" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "bg-white dark:bg-technik-950 border-technik-900 dark:border-technik-50 text-technik-950 dark:text-technik-50 dark:border-technik-50" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "bg-white dark:bg-makler-950 border-makler-900 dark:border-makler-50 text-makler-950 dark:text-makler-50 dark:border-makler-50" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
])>
                <ul class="flex flex-col space-y-8 text-lg font-bold uppercase">
                    @if( \Illuminate\Support\Facades\Route::is('hausverwaltung.*') )
                        <a href="{{ route('hausverwaltung.home') }}" wire:navigate>Start</a>
                        <a href="{{ route('hausverwaltung.leistungen') }}" wire:navigate>Leistungen</a>
                        <a href="{{ route('hausverwaltung.service') }}" wire:navigate>Service</a>
                        <a href="{{ route('hausverwaltung.karriere') }}" wire:navigate>Karriere</a>
                        <a href="{{ route('hausverwaltung.kontakt') }}" wire:navigate>Kontakt</a>
                    @endif

                    @if( \Illuminate\Support\Facades\Route::is('immobilien.*') )
                        <a href="{{ route('immobilien.home') }}" wire:navigate>Start</a>
                        <a href="{{ route('immobilien.immobiliensuche') }}" wire:navigate>Immobiliensuche</a>
                        <a href="{{ route('immobilien.ueber-uns') }}" wire:navigate>Über uns</a>
                        <a href="{{ route('immobilien.karriere') }}" wire:navigate>Karriere</a>
                        <a href="{{ route('immobilien.kontakt') }}" wire:navigate>Kontakt</a>
                    @endif


                    @if( \Illuminate\Support\Facades\Route::is('technik.*') )
                        <a href="{{ route('technik.home') }}" wire:navigate>Start</a>
                        <a href="{{ route('technik.karriere') }}" wire:navigate>Karriere</a>
                        <a href="{{ route('technik.kontakt') }}" wire:navigate>Kontakt</a>
                    @endif
                </ul>
            </div>

            <div @class([
        "grid grid-cols-2 w-full border-b-2 bg-white",
        "border-logo-900 dark:border-logo-50 dark:bg-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "border-technik-900 dark:border-technik-50 dark:bg-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "border-makler-900 dark:border-makler-50 dark:bg-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
])>
                @if( ! \Illuminate\Support\Facades\Route::is('hausverwaltung.*') )
                    <a href="{{ route('hausverwaltung.home') }}" class="aspect-square flex items-center justify-center"
                       wire:navigate>
                        <img src="{{ asset('logos/bereal_immobilien_vertical.svg') }}" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_immobilien_vertical_white.svg') }}"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif

                @if( ! \Illuminate\Support\Facades\Route::is('immobilien.*') )
                    <a href="{{ route('immobilien.home') }}" class="aspect-square flex items-center justify-center"
                       wire:navigate>
                        <img src="{{ asset('logos/bereal_makler_vertical.svg') }}" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_makler_vertical_white.svg') }}"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif


                @if( ! \Illuminate\Support\Facades\Route::is('technik.*') )
                    <a href="{{ route('technik.home') }}" class="aspect-square flex items-center justify-center"
                       wire:navigate>
                        <img src="{{ asset('logos/bereal_technik_vertical.svg') }}" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_technik_vertical_white.svg') }}"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@script
<script>
    Alpine.data('navControl', () => {
        return {
            open: false
        }
    })
</script>
@endscript