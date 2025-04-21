<x-section>
    <section @class([
        "mb-12",
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
    ])>
        <div class="max-w-md w-full mb-24">
            <x-headings>
                <x-slot name="tag">{{ $pagesSettings->contact_header }}</x-slot>
                {{ $pagesSettings->contact_subheader }}
            </x-headings>
        </div>
        <div data-aos="fade" data-aos-delay="600" class="prose max-w-full">

            @if(!is_array($preparedText))
                {!! html_entity_decode( $preparedText ) !!}
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-12 w-full">
                    <div>{!! $preparedText['firstHalf'] !!}</div>
                    <div>{!! $preparedText['secondHalf'] !!}</div>
                </div>
            @endif
        </div>
    </section>


    <livewire:contact-form lazy company="$company"/>


    <livewire:contact-departments lazy company="$company"/>


</x-section>
