<x-section class="bg-logo-500/5">
    <div class="px-4 py-24 lg:px-0 lg:max-w-4xl xl:max-w-6xl mx-auto">
        <div @class(['md:max-w-sm lg:max-w-lg'])>
            <x-headings>
                <x-slot name="tag">{{ $header }}</x-slot>
                {{ $subheader }}
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

        <div>
            <livewire:subparts.services/>
        </div>

        <div data-aos="fade-up" data-aos-delay="600" class="flex justify-center mt-8">
            <x-button href="{{ route('hausverwaltung.service') }}">alle services</x-button>
        </div>
    </div>
</x-section>
