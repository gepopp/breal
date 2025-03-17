<?php

use function Livewire\Volt\{mount}

?>
<header class="p-4 flex justify-between bg-white dark:bg-logo-950 dark:text-white">
    <div x-data="logoPlayer">
        <div id="navbar-canvas" class="h-12 -ml-2"></div>
    </div>

    <div class="hidden flex items-center w-1/3 relative">
        <nav class="menu overflow-hidden text-xl font-bold text-logo/90 dark:text-white">
            <a href="#" class="menu-item">
                <div>
                    <span class="menu-item-text">verwaltung</span>
                </div>
            </a>

            <a href="#" class="menu-item">
                <div>
                    <span class="menu-item-text">immobilien</span>
                </div>
            </a>

            <a href="#" class="menu-item">
                <div>
                    <span class="menu-item-text">technik</span>
                </div>
            </a>
        </nav>
    </div>


    <div class="hidden">
        <x-button>kontakt</x-button>
    </div>

    <div class="flex items-center" x-data="{ show:false }">
        <div x-on:click="show = !show" class="flex items-center px-2 relative">
            <svg class="size-4 text-logo dark:text-white" data-slot="icon" fill="none" stroke-width="3" stroke="currentColor"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
            </svg>
            <p class="uppercase !text-sm font-bold leading-1 -mb-1 text-logo dark:text-white">verwaltung</p>

            <div x-show="show" x-collapse class="absolute top-full w-full mt-2 pb-2 px-2 space-y-2 bg-white dark:bg-logo-950 shadow">
                <div class="flex items-center mr-4">
                    <svg class="size-4 shrink-0 text-logo dark:text-white -ml-2" data-slot="icon" fill="none" stroke-width="3"
                         stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                    </svg>
                    <p class="uppercase font-bold leading-1 -mb-1 text-logo dark:text-white !text-sm">immobilien</p>
                </div>
                <div class="flex items-center mr-4">
                    <svg class="size-4 shrink-0 text-logo dark:text-white -ml-2" data-slot="icon" fill="none" stroke-width="3"
                         stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                    </svg>
                    <p class="uppercase font-bold leading-1 -mb-1 text-logo dark:text-white !text-sm">technik</p>
                </div>
            </div>

        </div>
        <svg x-data="{ open: false }" x-on:click="open = !open" class="size-6 text-logo dark:text-white" xmlns="http://www.w3.org/2000/svg" width="28" height="15" viewBox="0 0 28 15">
            <line :class="open ? 'hidden' : ''" class="transition-all duration-300 ease-in-out" x2="20" transform="translate(6.5 1.5)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="3"/>
            <line :class="open ? 'rotate-45 origin-center scale-x-110' : ''" class="transition-all duration-300 ease-in-out" x2="25" transform="translate(1.5 7.5)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="3"/>
            <line :class="open ? '-rotate-45 origin-center scale-x-110' : ''" class="transition-all duration-300 ease-in-out" x2="25" transform="translate(1.5 7.5)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="3"/>
            <line :class="open ? 'hidden' : ''"  x2="18" class="transition-all duration-300 ease-in-out" transform="translate(8.5 13.5)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="3"/>
        </svg>
    </div>

</header>
@script
<script>
    Alpine.data('logoPlayer', () => {
        return {
            lottie: null,
            init() {
                this.lottie = lottie.loadAnimation({
                    container: document.getElementById('navbar-canvas'),
                    renderer: 'svg',
                    autoplay: false,
                    speed: .7,
                    loop: false,
                    path: '{{ asset('logo-auf-ebenen-fr-animation.json') }}'
                });

                this.lottie.play();
            }
        }
    })
</script>
@endscript