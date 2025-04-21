<x-section>
    <section @class([
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
    ])>
        <div class="max-w-md w-full">
            <x-headings>
                <x-slot name="tag">{{ $pagesSettings->team_header }}</x-slot>
                {{ $pagesSettings->team_subheader }}
            </x-headings>
            <div data-aos="fade" data-aos-delay="600">
                {!! $pagesSettings->team_introtext !!}
            </div>
        </div>
    </section>

    <livewire:contact-departments lazy :withIntro="false" company="$company"/>
</x-section>
