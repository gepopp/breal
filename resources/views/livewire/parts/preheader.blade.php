<div @class([ "py-2 px-4 hidden md:block",
 "bg-logo-900 dark:bg-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
 "bg-technik-900 dark:bg-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
 "bg-makler-900 dark:bg-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
 ])>
    <div class="flex justify-between items-center text-white">
        <div class="flex items-center space-x-2">
            <svg class="size-6" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"></path>
            </svg>
            <a href="https://realonline.bontus-eybel.at" class="font-bold uppercase" target="_blank">Kundenlogin</a>
        </div>

        <div class="flex items-center divide-x-2 divide-white">
            @if( ! \Illuminate\Support\Facades\Route::is('hausverwaltung.*') )
                <a href="{{ route('hausverwaltung.home') }}" class="px-4" wire:navigate>
                    <img src="{{ asset('logos/bereal_immobilien_white.svg') }}" class="h-5"/>
                </a>
            @endif

            @if( ! \Illuminate\Support\Facades\Route::is('immobilien.*') )
                <a href="{{ route('immobilien.home') }}" class="px-4" wire:navigate>
                    <img src="{{ asset('logos/bereal_makler_white.svg') }}" class="h-5"/>
                </a>
            @endif


            @if( ! \Illuminate\Support\Facades\Route::is('technik.*') )
                <a href="{{ route('technik.home') }}" class="px-4" wire:navigate>
                    <img src="{{ asset('logos/bereal_technik_white.svg') }}" class="h-5"/>
                </a>
            @endif
        </div>

    </div>
</div>
