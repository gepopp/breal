<x-section class="bg-logo">
    <div @class(['flex items-center mt-12 md:mt-0'])>
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
    <div class="lg:max-w-4xl xl:max-w-6xl mx-auto min-h-[60vh] mt-12">
        <div class="min-h-[60vh] box-border hidden xl:block relative left-0 w-[calc(100vw-(50vw-36rem))] pr-6">
            <div class="swiper swiperXl">
                <div class="swiper-wrapper">
                    @foreach($references as $ref)
                        <x-swiper-slide :ref="$ref"/>
                    @endforeach
                </div>
                <x-swiper-navs/>
            </div>
            <script>
                const swiperXl = new Swiper('.swiperXl', {
                    // Optional parameters
                    loop: true,
                    slidesPerView: 3,
                    spaceBetween: 30,
                    navigation: {
                        nextEl: '.button-next',
                        prevEl: '.button-prev',
                    },
                    pagination: {
                        el: ".pagination",
                        type: "fraction",
                    },
                });
            </script>
        </div>

        <div class="box-border hidden lg:block xl:hidden relative left-0 w-[calc(100vw-(50vw-28rem))] text-right pr-6">
            <div class="swiper swiperLG">
                <div class="swiper-wrapper">
                    @foreach($references as $ref)
                        <x-swiper-slide :ref="$ref"/>
                    @endforeach
                </div>
                <x-swiper-navs/>
                <div class="swiper-pagination !text-white"></div>
            </div>
            <script>
                const swiperLG = new Swiper('.swiperLG', {
                    // Optional parameters
                    loop: true,
                    slidesPerView: 3,
                    spaceBetween: 30,
                    navigation: {
                        nextEl: '.button-next',
                        prevEl: '.button-prev',
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        type: "fraction",
                    },
                });
            </script>
        </div>

        <div class="box-border lg:hidden">
            <div class="swiper swiperXS">
                <div class="swiper-wrapper">
                    @foreach($references as $ref)
                        <x-swiper-slide :ref="$ref"/>
                    @endforeach
                </div>
                <x-swiper-navs/>
                <div class="swiper-pagination !text-white"></div>
            </div>
            <script>
                const swiperXS = new Swiper('.swiperXS', {
                    // Optional parameters
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 30,
                    navigation: {
                        nextEl: '.button-next',
                        prevEl: '.button-prev',
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        type: "fraction",
                    },
                });
            </script>
        </div>
    </div>
</x-section>

@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endassets