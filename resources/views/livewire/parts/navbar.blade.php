@php
    use Illuminate\Support\Facades\Route;
@endphp
<div class="relative z-50" id="navbar">
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
                        <img src="{{ asset('logos/bereal_immobilien.svg') }}" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_immobilien_white.svg') }}"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>

                @elseif( Route::is('makler.*') )
                    <a href="{{ route('makler.home') }}">
                        <img src="{{ asset('logos/bereal_makler.svg') }}" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_makler_white.svg') }}"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>

                @elseif( Route::is('technik.*') )
                    <a href="{{ route('technik.home') }}">
                        <img src="{{ asset('logos/bereal_technik.svg') }}" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_technik_white.svg') }}"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>

                @else
                    <a href="{{ route('makler.home') }}">
                        <img src="{{ asset('logos/bereal.svg') }}" class="h-6 sm:h-8 md:h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_white.svg') }}"
                             class="h-6 sm:h-8 md:h-10 hidden dark:block"/>
                    </a>
                @endif
            </div>


            <nav class="hidden lg:flex mb-px space-x-12 justify-between items-center font-bold text-logo-500 dark:text-white">
                @if( !Route::is('makler.*', 'technik.*') )
                    <div class="relative group">
                        <div class="overflow-hidden">
                            <a href="{{ route('hausverwaltung.leistungen') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600">
                                <div @class(["font-black" => Route::is('hausverwaltung.leistungen')])>
                                    <span class="menu-item-text pointer-events-none block relative">Leistungen</span>
                                </div>
                            </a>
                        </div>
                        <div class="group-hover:block hidden transition-all duration-300">
                            <livewire:parts.competence-dropdown/>
                        </div>
                    </div>
                    <div class="relative group">
                        <div class="overflow-hidden">
                            <a href="{{ route('hausverwaltung.service') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600">
                                <div @class(["font-black" => Route::is('hausverwaltung.service')])>
                                    <span class="menu-item-text pointer-events-none block relative">Service</span>
                                </div>
                            </a>
                            <div class="group-hover:block hidden transition-all duration-300">
                                <livewire:parts.menu-service-dropdown/>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('hausverwaltung.karriere') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('hausverwaltung.karriere')])>
                                    <span class="menu-item-text pointer-events-none block relative">Karriere</span>
                                </div>
                            </a>
                        </div>
                    </div>


                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('hausverwaltung.team') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('hausverwaltung.team')])>
                                    <span class="menu-item-text pointer-events-none block relative">Team</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('hausverwaltung.faq') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('hausverwaltung.team')])>
                                    <span class="menu-item-text pointer-events-none block relative">FAQ</span>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if( Route::is('makler.*') )

                    @if(\App\Models\Realty::count())
                        <div class="relative">
                            <div class="overflow-hidden">
                                <a href="{{ route('makler.immobiliensuche') }}"
                                   class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                    <div @class(["font-black" => Route::is('makler.immobiliensuche')])>
                                        <span class="menu-item-text pointer-events-none block relative">Immobiliensuche</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

{{--                    <div class="relative">--}}
{{--                        <div class="overflow-hidden">--}}
{{--                            <a href="{{ route('makler.leistungen') }}" --}}
{{--                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">--}}
{{--                                <div @class(["font-black" => Route::is('makler.leistung')])>--}}
{{--                                    <span class="menu-item-text pointer-events-none block relative">Leistungen</span>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('makler.karriere') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('makler.karriere')])>
                                    <span class="menu-item-text pointer-events-none block relative">Karriere</span>
                                </div>
                            </a>
                        </div>
                    </div>


                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('makler.team') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('makler.team')])>
                                    <span class="menu-item-text pointer-events-none block relative">Team</span>
                                </div>
                            </a>
                        </div>
                    </div>

                @endif


                @if( Route::is('technik.*') )

                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('technik.leistungen') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('technik.leistung')])>
                                    <span class="menu-item-text pointer-events-none block relative">Leistungen</span>
                                </div>
                            </a>
                        </div>
                    </div>


                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('technik.karriere') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('technik.karriere')])>
                                    <span class="menu-item-text pointer-events-none block relative">Karriere</span>
                                </div>
                            </a>
                        </div>
                    </div>


                    <div class="relative">
                        <div class="overflow-hidden">
                            <a href="{{ route('technik.team') }}"
                               class="menu-item relative uppercase cursor-pointer text-logo-600 ">
                                <div @class(["font-black" => Route::is('technik.team')])>
                                    <span class="menu-item-text pointer-events-none block relative">Team</span>
                                </div>
                            </a>
                        </div>
                    </div>
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
        <div x-show="open" x-collapse.duration.500ms x-cloak
             class="absolute top-full w-full shadow-xl lg:hidden bg-white z-[9999]">
            <div @class([
        "border-b-2 shadow lg:max-w-4xl xl:max-w-6xl mx-auto flex justify-between items-center p-4 w-full",
        "bg-white dark:bg-logo-950 border-logo-900 dark:border-logo-50 text-logo-950 dark:text-logo-50 dark:border-logo-50" => Route::is('hausverwaltung.*'),
        "bg-white dark:bg-technik-950 border-technik-900 dark:border-technik-50 text-technik-950 dark:text-technik-50 dark:border-technik-50" => Route::is('technik.*'),
        "bg-white dark:bg-makler-950 border-makler-900 dark:border-makler-50 text-makler-950 dark:text-makler-50 dark:border-makler-50" => Route::is('makler.*'),
])>
                <ul class="flex flex-col space-y-8 text-lg font-bold uppercase">
                    @if( Route::is('hausverwaltung.*') )
                        <a href="{{ route('hausverwaltung.leistungen') }}" >Leistungen</a>
                        <a href="{{ route('hausverwaltung.service') }}" >Service</a>
                        <a href="{{ route('hausverwaltung.karriere') }}" >Karriere</a>
                        <a href="{{ route('hausverwaltung.kontakt') }}" >Kontakt</a>
                        <a href="{{ route('hausverwaltung.team') }}" >Team</a>
                    @endif

                    @if( Route::is('makler.*') )
                        <a href="{{ route('makler.immobiliensuche') }}" >Immobiliensuche</a>
                        <a href="{{ route('makler.karriere') }}" >Karriere</a>
                        <a href="{{ route('makler.kontakt') }}" >Kontakt</a>
                        <a href="{{ route('makler.team') }}" >Team</a>
                    @endif


                    @if( Route::is('technik.*') )
                        <a href="{{ route('technik.karriere') }}" >Karriere</a>
                        <a href="{{ route('technik.kontakt') }}" >Kontakt</a>
                        <a href="{{ route('technik.team') }}" >Team</a>
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
                        <img src="{{ asset('logos/bereal_immobilien_vertical.svg') }}" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_immobilien_vertical_white.svg') }}"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif

                @if( ! Route::is('makler.*') )
                    <a href="{{ route('makler.home') }}"
                       class="aspect-square sm:aspect-[4/3] flex items-center justify-center"
                       >
                        <img src="{{ asset('logos/bereal_makler_vertical.svg') }}" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_makler_vertical_white.svg') }}"
                             class="h-10 dark:block hidden"/>
                    </a>
                @endif


                @if( ! Route::is('technik.*') )
                    <a href="{{ route('technik.home') }}"
                       class="aspect-square sm:aspect-[4/3] flex items-center justify-center"
                       >
                        <img src="{{ asset('logos/bereal_technik_vertical.svg') }}" class="h-10 dark:hidden"/>
                        <img src="{{ asset('logos/bereal_technik_vertical_white.svg') }}"
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
                    <img src="{{ asset('logos/bereal_immobilien_icon_white.svg') }}" class="h-5">
                </div>
                <div class="flex items-center space-x-2 text-white">
                    <svg class="size-6" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"></path>
                    </svg>
                    <a href="https://realonline.bontus-eybel.at" class="font-bold uppercase"
                       target="_blank">Kundenlogin</a>
                </div>
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


    const menuItems = [...document.querySelectorAll(".menu-item")];

    menuItems.forEach(item => {
        let word = item.children[0].children[0].innerText.split('');
        item.children[0].innerHTML = '';
        word.forEach((letter, idx) => {
            item.children[0].innerHTML += `<span style="--index: ${idx};">${letter}</span>`;
        })
        let cloneDiv = item.children[0].cloneNode(true);
        cloneDiv.style.position = "absolute";
        cloneDiv.style.top = "0px";
        cloneDiv.style.left = "0px";
        item.appendChild(cloneDiv);
    })
</script>
@endscript