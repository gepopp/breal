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
                      d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"></path>
            </svg>
            <a href="mailto:office@bereal.at" class="font-bold">office@bereal.at</a>
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
