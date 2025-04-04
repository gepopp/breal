<div @class([
        "mx-auto grid grid-cols-1 md:grid-cols-2 relative w-full px-4"
])>
    <div @class(['flex justify-center items-center md:min-h-[70vh] mt-12 md:mt-0'])>
        <div @class(['md:max-w-sm lg:max-w-lg lg:my-48'])>
            <x-headings>
                <x-slot name="tag">{{ $header }}</x-slot>
                {{ $subheader }}
            </x-headings>
            <div data-aos="fade" data-aos-delay="600" class="prose">
                {!! html_entity_decode( $intro ) !!}
            </div>
        </div>
    </div>
    <div @class(['order-first md:order-last relative overflow-hidden'])>

        @if(!is_null($media))
            <img src="{{ $media?->getUrl('layout') }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $alt }}" data-aos="fade"
                 data-aos-delay="600" class="h-full w-auto object-cover"/>
        @endif

        <div class="absolute bottom-0 left-0 w-[20%] h-full hidden md:block">
            <svg @class(['text-white dark:text-logo-950 h-full w-auto shadow']) fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                 version="1.1" viewBox="0 0 85.3 501">
                <polygon points="0 501 0 501 0 0 85.3 0 0 501"/>
            </svg>
        </div>
    </div>
</div>
