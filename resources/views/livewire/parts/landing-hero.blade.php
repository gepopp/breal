<div @class([
        "mx-auto grid grid-cols-1 md:grid-cols-2 relative w-full"
])>
    <div @class(['flex justify-center items-center md:min-h-[70vh] mt-12 md:mt-0'])>
        <div @class(['md:max-w-sm lg:max-w-lg lg:my-48 px-4'])>
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

        @if($media?->count() == 1)
            @php
                $media = $media->first();
            @endphp
            <img src="{{ $media?->getUrl('layout') }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $alt }}" data-aos="fade"
                 data-aos-delay="600" class="h-full w-auto object-cover"/>

        @elseif($media?->count() > 1)

            <div class="swiper">
                <div class="swiper-wrapper">
                    @foreach($media as $image)
                        <div class="swiper-slide">
                            <img scr="{{ $image->getUrl('slider') }}" alt="{{ $alt }}" srcset="{{ $image->getSrcset() }}" class="h-full w-auto object-cover"/>
                        </div>
                    @endforeach
                </div>
            </div>
            <script>
                const swiperXS = new Swiper('.swiper', {
                    // Optional parameters
                    loop: true,
                    slidesPerView: 1,
                    speed: 800,
                    autoplay: {
                        delay: 2000
                    }
                });
            </script>
        @endif

        <div class="absolute bottom-0 left-0 w-[20%] h-full hidden md:block">
            <svg @class(['text-white dark:text-logo-950 h-full w-auto shadow z-[9999] relative']) fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                 version="1.1" viewBox="0 0 85.3 501">
                <polygon points="0 501 0 501 0 0 85.3 0 0 501"/>
            </svg>
        </div>
    </div>
</div>
@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endassets