<x-section>
    <div @class(['md:max-w-sm lg:max-w-lg lg:my-48 px-4'])>
        <x-headings>
            <x-slot name="tag">{{ $header }}</x-slot>
            {{ $subheader }}
        </x-headings>
        <div data-aos="fade" data-aos-delay="600" class="prose">
            {!! html_entity_decode( $text ) !!}
        </div>
    </div>

    <div class="py-12">
        <livewire:contact-form/>
    </div>
</x-section>