<div @class([
        "mx-auto grid grid-cols-1 md:grid-cols-2 relative w-full"
])>
    <div x-data="{
        init(){
            if(window.innerWidth > 767 && $refs.wrapper){
                 var height = $refs.textCol.clientHeight;
                 $refs.wrapper.style.height = `${height}px`;
                 setTimeout(() => $dispatch('setheight', height), 500);
            }


            window.addEventListener('resize', () => {
                 if(window.innerWidth > 767 && $refs.wrapper){
                     var height = $refs.textCol.clientHeight;
                     $refs.wrapper.style.height = `${height}px`;
                     setTimeout(() => $dispatch('setheight', height), 500);
                }
            })

        }
    }"
         x-ref="textCol"
            @class(['flex justify-center items-center md:min-h-[70vh] mt-12 md:mt-0'])>
        <div @class(['md:max-w-sm lg:max-w-lg lg:my-48 px-4'])>
            <x-headings>
                <x-slot name="tag">{{ $settings->hero_header }}</x-slot>
                {{ $settings->hero_subheader }}
            </x-headings>
            <div data-aos="fade" data-aos-delay="600" class="prose">
                {!! $settings->hero_introtext !!}
            </div>
        </div>
    </div>
    <div @class(['order-first md:order-last relative overflow-hidden font-logo'])>

        @if($media?->count() == 1)
            @php
                $media = $media->first();
            @endphp
            <img src="{{ $media?->getUrl('layout') }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $settings->hero_image_alt }}" data-aos="fade"
                 data-aos-delay="600" class="h-full w-auto object-cover"/>

        @elseif($media?->count() > 1)

            <div x-ref="swiper" class="swiper swiperSolo min-h-full">
                <div x-ref="wrapper" class="swiper-wrapper min-h-full flex-1">
                    @foreach($media as $image)
                        <div class="swiper-slide min-h-full">
                            <img scr="{{ $image->getUrl('slider') }}" alt="{{ $settings->hero_image_alt }}" srcset="{{ $image->getSrcset() }}" class="h-full w-auto object-cover"/>
                        </div>
                    @endforeach
                </div>
            </div>
            <script>
                const swiperSolo = new Swiper('.swiperSolo', {
                    // Optional parameters
                    loop: true,
                    slidesPerView: 1,
                    speed: 800,
                    autoplay: {
                        delay: {{ $settings->hero_speed }}
                    }
                });
            </script>
        @endif

        <div class="absolute bottom-0 left-0 w-[20%] h-full hidden md:block">
            <svg @class(['text-white dark:text-logo-950 h-full w-auto z-[9999] relative']) fill="currentColor" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 85.3 501">
                <polygon points="0 501 0 501 0 0 85.3 0 0 501"/>
            </svg>
        </div>
    </div>
</div>