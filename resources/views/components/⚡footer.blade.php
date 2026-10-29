<?php

use App\Enums\CompaniesEnum;
use App\Services\CompanyInfo;
use Livewire\Component;

new class extends Component
{
    public string $company = '';

    public function mount(): void
    {
        $this->company = CompaniesEnum::getByRoute();
    }

    /**
     * @return array<string, mixed>
     */
    public function with(): array
    {
        return ['section' => CompanyInfo::section($this->company)];
    }
};
?>

<footer class="bg-logo pt-24 flex flex-col items-center justify-center text-white px-4">
    <div class="lg:max-w-4xl xl:max-w-6xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 w-full">
        <div>
            <a href="{{ route('hausverwaltung.home') }}">
                <img src="{{ asset('be_Logo_RGB_white_400.svg') }}" alt="be real" class="h-16"/>
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

                <a href="{{ route('verwalterservice') }}" wire:navigate class="lowercase cursor-pointer font-extrabold overflow-hidden text-2xl hover:tracking-wider transition-all duration-300">
                    {{ __('Corporate Service') }}
                </a>

            </nav>
        </div>


        <div class="flex flex-col space-y-6">
            <p class="!mb-0 font-bold">{{ $section['name'] }}</p>
            <p class="!mb-0">{{ config('company.address.street') }}</p>
            <p class="!mb-0">{{ config('company.address.postal_code') }} {{ config('company.address.city') }}</p>
            <p class="!mb-0">{{ __('footer.phone') }}: <a href="{{ \App\Services\CompanyInfo::phoneUri() }}">{{ \App\Services\CompanyInfo::phone() }}</a></p>
            <p class="!mb-0">{{ __('footer.fax') }}: {{ \App\Services\CompanyInfo::fax() }}</p>
            <p class="!mb-0">E-Mail: <a href="{{ \App\Services\CompanyInfo::mailtoUri($section['email']) }}">{{ $section['email'] }}</a></p>
            <p class="!mb-0">{{ __('footer.opening_hours') }}:
                @foreach(\App\Services\CompanyInfo::hours('opening') as $days => $times)
                    <br>{{ $days }}: {{ $times }}
                @endforeach
            </p>
            <p class="!mb-0">{{ __('footer.phone_hours') }}:
                @foreach(\App\Services\CompanyInfo::hours('phone') as $days => $times)
                    <br>{{ $days }}: {{ $times }}
                @endforeach
            </p>
        </div>
    </div>

    <div class="border-t border-white py-4 w-full mt-16 flex justify-center">
        <div class="lg:max-w-4xl xl:max-w-6xl w-full flex flex-col md:flex-row justify-between text-sm">
            <p class="!text-sm">&copy; {{ \App\Services\CompanyInfo::copyrightYears($company) }} {{ $section['legal_name'] }}</p>
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
