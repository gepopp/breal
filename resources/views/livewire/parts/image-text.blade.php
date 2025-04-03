<div @class([
        "mx-auto grid grid-cols-1 md:grid-cols-2 relative min-h-[70vh] w-full mb-24"
])>
    <div @class(['flex justify-center items-center md:min-h-[70vh]'])>
        <div @class(['md:max-w-sm lg:max-w-lg'])>
            <div data-aos="fade" class="prose">
                {!! html_entity_decode( $text ) !!}
            </div>
        </div>
    </div>
    <div @class(['order-last md:order-first relative overflow-hidden'])>

        @if(!is_null($media))
            <img src="{{ $media?->getUrl() }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $alt }}" data-aos="fade"
                 data-aos-delay="600" class="h-full w-auto object-cover"/>
        @endif

        <div class="absolute top-0 right-0 w-[20%] h-full hidden md:block flex justify-end">
            <svg @class(['text-white h-full w-auto shadow ml-auto -mr-px']) fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                 version="1.1" viewBox="0 0 85.3 501">
                <polygon class="st0" points="85.3 0 85.3 0 85.3 501 0 501 85.3 0"/>
            </svg>
        </div>
    </div>
</div>
