<x-section>
    <div @class(['md:max-w-sm lg:max-w-lg' => !is_array($preparedText), 'max-w-full'])>
        <x-headings>
            <x-slot name="tag">{{ $header }}</x-slot>
            {{ $subheader }}
        </x-headings>
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
    </div>

    <div>
        <livewire:subparts.timeline/>
    </div>
</x-section>
