<div class="mt-12 relative">
    <div data-aos="fade" class="mt-12 md:mt-24">

        <div class="inset-shadow-sm inset-shadow-logo/50 border border-logo relative">
            <div class="swiper timelineswiper max-h-[60vh]">
                <div class="swiper-wrapper  md:aspect-video">
                    @foreach($timeline as $key => $entry)
                        <div class="swiper-slide bg-transparent">
                            <div class="w-full h-full md:aspect-video flex justify-center items-center">
                                <div class="flex flex-col justify-center items-center max-w-3/4">
                                <span @class(['text-logo font-logo text-4xl md:text-7xl font-extrabold px-1.5 align-baseline rounded tracking-wide'])>
                                    {{ $entry->year }}
                                </span>
                                    <div>
                                        <h5 class="font-bold text-sm md:text-xl text-center text-logo">{{ $entry->title }}</h5>
                                        <p class="text-center mt-4 text-xs">{{ $entry->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="prev-slide cursor-pointer absolute top-0 left-1/2 -translate-y-1/2 -translate-x-1/2 w-12 aspect-square rounded-full bg-logo text-white flex justify-center items-center z-[9999] shadow-lg">
                <svg class="size-10 mb-1" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"></path>
                </svg>
            </div>

            <div class="next-slide cursor-pointer absolute bottom-0 left-1/2 translate-y-1/2 -translate-x-1/2 w-12 right-0 aspect-square rounded-full bg-logo text-white flex justify-center items-center z-[9999] shadow-lg">
                <svg class="size-10 mt-1" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
                </svg>
            </div>

        </div>
        <script>
            const timelineSwiper = new Swiper('.timelineswiper', {
                loop: true,
                slidesPerView: 1,
                speed: 800,
                spaceBetween: 30,
                navigation: {
                    nextEl: ".next-slide",
                    prevEl: ".prev-slide",
                    clickable: true
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                autoplay: {
                    delay: 2000
                }
            })
        </script>

    </div>
</div>
