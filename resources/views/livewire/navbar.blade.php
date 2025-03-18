<?php

use function Livewire\Volt\{mount}

?>
<div>
    <div x-data="logoPlayer" class="relative">
        <header class="p-4 flex justify-between bg-white dark:bg-logo-950 dark:text-white">
            <div>
                <div x-cloak x-show="!$flux.dark" id="navbar-canvas" class="h-12 -ml-2 sm:ml-0"></div>
                <div x-cloak x-show="$flux.dark" id="navbar-canvas-dark" class="h-12 -ml-2 sm:ml-0"></div>
            </div>

            <div x-cloak
                 x-show="complete"
                 x-transition:enter="transition ease-out duration-750"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="hidden sm:flex flex-1 max-w-1/2 md:max-w-sm ml-16 md:ml-0 items-end md:items-center md:mt-1 relative">
                <nav class="absolute flex w-full justify-between items-center overflow-hidden font-semibold text-logo-500 dark:text-white">
                    <a href="#" class="menu-item relative uppercase cursor-pointer text-logo-600 font-extrabold">
                        <div>
                            <span class="menu-item-text pointer-events-none block relative">verwaltung</span>
                        </div>
                    </a>

                    <a href="#" class="menu-item uppercase cursor-pointer relative">
                        <div>
                            <span class="menu-item-text pointer-events-none block relative">immobilien</span>
                        </div>
                    </a>

                    <a href="#" class="menu-item uppercase cursor-pointer relative">
                        <div>
                            <span class="menu-item-text pointer-events-none block relative">technik</span>
                        </div>
                    </a>
                </nav>
            </div>


            <div x-cloak
                 x-show="complete"
                 x-transition:enter="transition ease-out duration-750 delay-500"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="hidden md:flex flex-col items-center justify-end min-h-full mb-1">
                <x-button>kontakt</x-button>
            </div>

            <div x-cloak
                 x-show="complete"
                 x-transition:enter="transition ease-out duration-750"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="flex items-center sm:hidden" x-data="{ show:false }">
                <div x-on:click="show = !show" class="flex items-center px-2 relative">
                    <svg class="size-4 text-logo dark:text-white" data-slot="icon" fill="none" stroke-width="3"
                         stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
                    </svg>
                    <p class="uppercase !text-sm font-bold leading-1 -mb-1 text-logo dark:text-white">verwaltung</p>

                    <div x-cloak x-show="show" x-collapse
                         class="absolute top-full w-full mt-2 pb-2 px-2 space-y-2 bg-white dark:bg-logo-950 shadow">
                        <div class="flex items-center mr-4">
                            <svg class="size-4 shrink-0 text-logo dark:text-white -ml-2" data-slot="icon" fill="none"
                                 stroke-width="3"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                            </svg>
                            <p class="uppercase font-bold leading-1 -mb-1 text-logo dark:text-white !text-sm">immobilien</p>
                        </div>
                        <div class="flex items-center mr-4">
                            <svg class="size-4 shrink-0 text-logo dark:text-white -ml-2" data-slot="icon" fill="none"
                                 stroke-width="3"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                            </svg>
                            <p class="uppercase font-bold leading-1 -mb-1 text-logo dark:text-white !text-sm">technik</p>
                        </div>
                    </div>
                </div>

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
        </header>


        <div x-cloak x-show="submenu" x-collapse.duration.1000ms
             class="hidden sm:flex w-full p-4 bg-logo-500 text-white justify-center items-center space-x-4">
            <a href="#" class="hover:font-semibold transition-all duration-300">leistungen</a>
            <a href="#" class="hover:font-semibold transition-all duration-300">service</a>
            <a href="#" class="hover:font-semibold transition-all duration-300">karriere</a>
            <a href="#" class="md:hidden">kontakt</a>
        </div>

        <div x-cloak x-show="open" x-collapse
             class="absolute top-full left-0 w-full p-4 bg-logo-500 text-white flex justify-center items-center space-x-4">
            <a href="#">leistungen</a>
            <a href="#">service</a>
            <a href="#">karriere</a>
        </div>
    </div>
</div>


@script
<script>
    Alpine.data('logoPlayer', () => {
        return {
            lottie: null,
            lottieDark: null,
            complete: false,
            open: false,
            submenu: false,
            init() {
                this.lottie = lottie.loadAnimation({
                    container: document.getElementById('navbar-canvas'),
                    renderer: 'svg',
                    autoplay: false,
                    speed: .7,
                    loop: false,
                    path: '{{ asset('logo-auf-ebenen-fr-animation.json') }}'
                });

                this.lottieDark = lottie.loadAnimation({
                    container: document.getElementById('navbar-canvas-dark'),
                    renderer: 'svg',
                    autoplay: false,
                    speed: .7,
                    loop: false,
                    path: '{{ asset('logo-auf-ebenen-fr-animation-dark.json') }}'
                });

                this.lottie.addEventListener('drawnFrame', (e) => {
                    this.complete = e.currentTime > 20;
                    this.submenu = e.currentTime > 55;
                });

                this.lottieDark.addEventListener('drawnFrame', (e) => {
                    this.complete = e.currentTime > 20;
                    this.submenu = e.currentTime > 55;
                });


                this.lottie.play();
                this.lottieDark.play();
            }
        }
    })
</script>
@endscript