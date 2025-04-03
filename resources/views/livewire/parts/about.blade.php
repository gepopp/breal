<div @class(['bg-logo dark:bg-logo-950 py-24'])>
    <div @class([
        "mx-auto grid grid-cols-1 md:grid-cols-2 relative w-full"
])>
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
            @if(!is_null($media))
                <img src="{{ $media?->getUrl() }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $alt }}" data-aos="fade"
                     data-aos-delay="600" class="aspect-video max-w-2/3 object-cover rounded-xl shadow-lg shadow-white/10"/>
            @endif
        </div>
    </div>
    <div class="flex justify-center">
        <x-button href="{{ route('hausverwaltung.kontakt') }}" :ondark="true">kontakt</x-button>
    </div>

</div>
