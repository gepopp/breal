<div class="bg-logo-500/20">
    <div class="-mx-4 px-8 py-24 lg:px-0 lg:max-w-4xl xl:max-w-6xl mx-auto">
        <div @class(['md:max-w-sm lg:max-w-lg'])>
            <x-headings>
                <x-slot name="tag">{{ $header }}</x-slot>
                {{ $subheader }}
            </x-headings>
        </div>
        <div data-aos="fade" data-aos-delay="600" class="prose grid grid-cols-1 md:grid-cols-2 md:gap-24">
            {!! html_entity_decode( $text ) !!}
        </div>

        <div>
            <livewire:subparts.services/>
        </div>

        <div class="flex justify-center mt-8">
            <x-button href="{{ route('hausverwaltung.service') }}">alle services</x-button>
        </div>
    </div>
</div>
