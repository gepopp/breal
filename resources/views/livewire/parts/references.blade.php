<x-section class="bg-logo">
    <div @class(['flex items-center mt-12 md:mt-0 max-w-full'])>
        <div @class(['md:max-w-sm lg:max-w-lg' => !is_array($preparedText), 'max-w-full'])>
            <x-headings :ondark="true">
                <x-slot name="tag">{{ $header }}</x-slot>
                {!! $subheader !!}
            </x-headings>
            <div data-aos="fade" data-aos-delay="600" class="prose max-w-full !text-white">

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
    </div>
    <div class="lg:max-w-4xl xl:max-w-6xl max-w-full mx-auto mt-12 md:mt-24">
        <div class="min-h-[60vh] box-border relative left-0 w-screen lg:w-[calc(100vw-(50vw-28rem))] xl:w-[calc(100vw-(50vw-36rem))] sm:pl-8 lg:pl-0 lg:pr-6">
            <div class="swiper swiperXl">
                <div class="swiper-wrapper">
                    @foreach($references as $ref)
                        <x-swiper-slide :ref="$ref"/>
                    @endforeach
                </div>
                <div class="hidden absolute md:flex inset-0 md:bg-linear-to-r from-transparent from-[75%] to-logo z-10  justify-end items-center pointer-events-none mr-8 lg:mr-0">
                    <div class="flex flex-col bg-white rounded-l-full bg-white shadow shadow-white -translate-y-1/2">
                        <div class="button-next w-8 h-16 flex items-center pointer-events-auto cursor-pointer">
                            <svg class="sitze-4 text-logo" data-slot="icon" fill="none" stroke-width="3"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                const swiperXl = new Swiper('.swiperXl', {
                    // Optional parameters
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 10,
                    navigation: {
                        nextEl: '.button-next',
                        prevEl: '.button-prev',
                    },
                    pagination: {
                        el: ".pagination",
                        type: "fraction",
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1280    :{
                            slidesPerView: 4,
                            spaceBetween: 30,
                        }
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: true,
                    }
                });
            </script>
        </div>
    </div>
</x-section>

@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endassets