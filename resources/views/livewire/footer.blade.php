<?php

use function Livewire\Volt\{state};

state();

?>

<footer class="bg-logo pt-24 flex flex-col items-center justify-center text-white px-4">
    <div class="lg:max-w-4xl xl:max-w-6xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 w-full">
        <div>
            <a href="{{ route('hausverwaltung.home') }}">
                <img src="{{ asset('be_Logo_RGB_white_400.svg') }}" class="h-16"/>
            </a>
            <div class="mt-12">
                <p>
                    {{ __('footer.tagline') }}
                </p>
            </div>
        </div>


        <div class="flex md:justify-center">
            <nav class="font-semibold text-white flex flex-col space-y-6">
                <a href="{{ route('hausverwaltung.home') }}" wire:navigate class="lowercase cursor-pointer font-extrabold overflow-hidden text-2xl hover:tracking-wider transition-all duration-300">
                    {{ __('navigation.real_estate_short') }}
                </a>

                <a href="{{ route('makler.home') }}" wire:navigate class="lowercase cursor-pointer font-extrabold overflow-hidden text-2xl hover:tracking-wider transition-all duration-300">
                    {{ __('footer.broker') }}
                </a>

                <a href="{{ route('technik.home') }}" wire:navigate class="lowercase cursor-pointer font-extrabold overflow-hidden text-2xl hover:tracking-wider transition-all duration-300">
                    {{ __('navigation.technology_short') }}
                </a>

            </nav>
        </div>


        <div class="flex flex-col space-y-6">
            <p class="!mb-0">Franz Josefs Kai 65/Mezzanin</p>
            <p class="!mb-0">1010 Wien</p>
            <p class="!mb-0">{{ __('footer.phone') }}: +43 1 535 36 19</p>
            <p class="!mb-0">{{ __('footer.fax') }}: +43 1 535 64 28</p>
            <p class="!mb-0">E-Mail: <a href="mail:office@bereal-immobilien.at">office@bereal-immobilien.at</a></p>
            <p class="!mb-0">{{ __('footer.hours_mon_thu') }}: 08:00-16:00
                <br>
                {{ __('footer.hours_fri') }}: 08:00-12:00</p>
        </div>
    </div>

    <div class="border-t border-white py-4 w-full mt-16 flex justify-center">
        <div class="lg:max-w-4xl xl:max-w-6xl w-full flex flex-col md:flex-row justify-between text-sm">
            <p class="!text-sm">&copy; {{ now()->format('Y') }} be real Immobilienmanagement GmbH</p>
            <div class="flex flex-col md:flex-row md:divide-x divide-white justify-between order-first md:order-last mb-4">
                <a @class(['md:px-4']) href="{{ route('datenschutz') }}" wire:navigate @class([ 'font-bold' => request()->is('datenschutz') ])>{{ __('footer.privacy') }}</a>
                <a @class(['md:px-4']) href="{{ route('barrierefreiheit') }}">{{ __('footer.accessibility') }}</a>
                <a @class(['md:px-4']) href="{{ route('impressum') }}"
                   @class([ 'md:pl-4', 'font-bold' => request()->is('impressum') ])
                   wire:navigate>{{ __('footer.imprint') }}</a>
            </div>
        </div>
    </div>

</footer>
