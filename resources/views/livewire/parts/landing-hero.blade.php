<div @class([
        "mx-auto grid grid-cols-1 md:grid-cols-2 relative min-h-[70vh] w-full mb-24"
])>
    <div @class(['flex justify-center items-center'])>
        <div @class(['md:max-w-sm'])>
            <x-headings>
                <x-slot name="tag">{{ $header }}</x-slot>
                {{ $subheader }}
            </x-headings>
            <div data-aos="fade" data-aos-delay="600">
                {!! html_entity_decode( $intro ) !!}
            </div>
        </div>
    </div>
    <div @class(['order-first md:order-last -mx-4 md:ml-0 mb-8 relative overflow-hidden'])>

        @if(!is_null($media))
            <img src="{{ $media?->getUrl() }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $alt }}" data-aos="fade"
                 data-aos-delay="600" class="h-full w-auto object-cover"/>
        @endif

        <div class="absolute top-0 left-0 w-[20%] h-full hidden md:block">
            <svg @class(['text-white h-full w-auto']) fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                 version="1.1" viewBox="0 0 85.3 501">
                <polygon class="st0" points="0 501 0 501 0 0 85.3 0 0 501"/>
            </svg>
        </div>
    </div>
</div>
