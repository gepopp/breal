<x-section class="bg-logo">
    <div @class(["mx-auto grid grid-cols-1 md:grid-cols-2 md:gap-12 lg:gap-24 relative w-full"])>
        <div @class(['flex justify-center items-center md:min-h-[70vh] mt-12 md:mt-0'])>
            <div @class(['md:max-w-sm lg:max-w-lg'])>
                <x-headings :ondark="true">
                    <x-slot name="tag">{{ $header }}</x-slot>
                    {{ $subheader }}
                </x-headings>
                <div data-aos="fade" data-aos-delay="600" class="prose !text-white">
                    {!! html_entity_decode( $text ) !!}
                </div>
            </div>
        </div>
        <div @class(['order-first md:order-last -mx-4 md:mx-0 md:ml-0 relative overflow-hidden flex items-center p-4'])>
            @if(!is_null($media) && is_null($video))
                <img src="{{ $media?->getUrl() }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $alt }}" data-aos="fade"
                     data-aos-delay="600"
                     class="aspect-video xl:max-w-3/4 xl:mx-auto object-cover rounded-xl shadow-lg shadow-white/10"/>
            @endif

            @if(!is_null($video))
                <div class="aspect-video w-full xl:mx-auto object-cover rounded-xl shadow-lg shadow-white/10 overflow-hidden z-[9999]">
                    {!! html_entity_decode( $video ) !!}
                </div>
            @endif
        </div>
    </div>
    <div class="flex justify-center mt-8">
        <x-button href="{{ route('hausverwaltung.kontakt') }}" :ondark="true">kontakt</x-button>
    </div>
</x-section>
