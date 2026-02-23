@php
    use Illuminate\Support\Facades\Route;
@endphp
<div @class([ "py-2 px-4 hidden md:block",
 "bg-logo-900 dark:bg-logo-950" => !Route::is('technik.*', 'makler.*'),
 "bg-technik-900 dark:bg-technik-950" => Route::is('technik.*'),
 "bg-makler-900 dark:bg-makler-950" => Route::is('makler.*'),
 ])>
    <div class="flex justify-between items-center text-white">
        <div class="flex items-center space-x-2">

            @auth
                <div>
                    @if(app()->getLocale() == 'de')
                        <a href="{{ language()->back('en') }}" class="font-bold uppercase">ENGLISH</a>
                    @else
                        <a href="{{ language()->back('de') }}" class="font-bold uppercase">DEUTSCH</a>
                    @endif
                </div>

                <div class="w-2 h-2 bg-white rounded last:hidden"></div>
            @endauth



            <a href="{{ route('hausverwaltung.kontakt') }}" class="font-bold uppercase" target="_blank">{{ __('navigation.contact') }}</a>
        </div>

        <div class="flex items-center">
            @if( ! Route::is('hausverwaltung.*') )
                <a href="{{ route('hausverwaltung.home') }}" class="px-4 block">
                    <img src="{{ asset('logos/bereal_immobilien_white.svg') }}" class="h-5"/>
                </a>
                <div class="w-2 h-2 bg-white rounded last:hidden"></div>
            @endif

            @if( ! Route::is('makler.*') )
                <a href="{{ route('makler.home') }}" class="px-4">
                    <img src="{{ asset('logos/bereal_makler_white.svg') }}" class="h-5"/>
                </a>
                <div class="w-2 h-2 bg-white rounded last:hidden"></div>
            @endif


            @if( ! Route::is('technik.*') )
                <a href="{{ route('technik.home') }}" class="px-4">
                    <img src="{{ asset('logos/bereal_technik_white.svg') }}" class="h-5"/>
                </a>
                <div class="w-2 h-2 bg-white rounded last:hidden"></div>
            @endif
        </div>

    </div>
</div>
