<x-section>
    <div @class(['md:max-w-sm lg:max-w-lg'])>
        <x-headings>
            <x-slot name="tag">{{ $header }}</x-slot>
            {{ $subheader }}
        </x-headings>
        <div data-aos="fade" data-aos-delay="600" class="prose">
            {!! html_entity_decode( $text ) !!}
        </div>
    </div>

    <div>
        <livewire:subparts.timeline/>
    </div>
</x-section>
