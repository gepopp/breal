<x-section>
    <section @class([
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
    ])>
        <div class="max-w-md w-full mb-24">
            <x-headings>
                <x-slot name="tag">{{ $pagesSettings->contact_header }}</x-slot>
                {{ $pagesSettings->contact_subheader }}
            </x-headings>
            <div data-aos="fade" data-aos-delay="600">
                {!! $pagesSettings->contact_introtext !!}
            </div>
        </div>
    </section>


    <livewire:contact-departments lazy company="$company"/>


    <livewire:contact-form lazy company="$company"/>

</x-section>
