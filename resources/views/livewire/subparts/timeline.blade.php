<div class="mt-12 relative max-w-screen">
    <div x-data="timeline" data-aos="fade" class="mt-12 md:mt-24">

        <div class="relative">
            <div x-ref="indices"
                 class="flex items-center overflow-x-scroll scrollbar scrollbar-none w-3/4 lg:w-1/2 mb-4 ml-auto">
                <span class="w-full h-[2px] bg-logo shrink-0 mx-2"></span>
                @foreach($timeline as $key => $entry)
                    <span class="flex items-center">
                     <span class="text-xl font-logo shrink-0 mx-2 rounded transition-all duration-300"
                           :class="active == {{ $key }} ? 'bg-logo text-white px-1.5' : 'bg-transparent text-logo'">{{ $entry->year }}</span>
                    @if($key < count($timeline) - 1 )
                            <span class="w-12 h-[2px] bg-logo shrink-0 mx-2"></span>
                        @endif
                </span>
                @endforeach
                <span class="w-full h-[2px] bg-logo shrink-0 mx-2"></span>
            </div>

            <div class="absolute inset-0 bg-linear-to-r from-transparent via-transparent via-80% to-white"></div>

        </div>


        <div x-intersect.once="initSlider" class="relative max-w-full overflow-hidden">
            <div class="swiper max-w-screen timelineswiper max-h-[60vh]">
                <div class="swiper-wrapper w-full">
                    @foreach($timeline as $key => $entry)
                        <div class="swiper-slide max-w-screen bg-transparent">
                            <div class="inset-0 flex justify-center items-center px-4 sm:px-8">
                                <div class="flex md:max-w-3/4 relative">
                                    <div class="w-1/3 lg:w-1/2">
                                        <img lazy src="{{ $entry->getFirstMediaUrl('*') }}" class="aspect-square w-full object-cover" alt="{{ $entry->title }}"/>
                                    </div>
                                    <div class="w-2/3 lg:w-1/2 flex flex-col justify-center ml-4 mt-6">
                                        <div class="relative">
                                            <h5 class="font-bold text-sm md:text-xl text-logo">{{ $entry->title }}</h5>
                                            <div class="hidden sm:block absolute top-0 -mt-6 left-0 -ml-20 w-36 h-[3px] bg-logo text-right">
                                                <span class="ml-auto pt-1">{{ $entry->year }}</span>
                                            </div>
                                        </div>
                                        <p class="!text-xs">{{ $entry->description }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="prev-slide cursor-pointer absolute top-1/2 left-2 w-6 md:w-12 aspect-square rounded-full bg-logo text-white flex justify-center items-center z-[9999] shadow-lg">
                <svg class="size-5 md:size-10" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"></path>
                </svg>
            </div>

            <div class="next-slide cursor-pointer absolute right-2 top-1/2 -translate-y-1/2 w-6 md:w-12 right-0 aspect-square rounded-full bg-logo text-white flex justify-center items-center z-[9999] shadow-lg">
                <svg class="size-5 md:size-10" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
                </svg>
            </div>

        </div>
    </div>
</div>
@script
<script>
    Alpine.data('timeline', () => ({
        slider: null,
        active: 0,
        init() {
            this.slider = new Swiper('.timelineswiper', {
                init: false,
                loop: true,
                slidesPerView: 1,
                speed: 800,
                spaceBetween: 30,
                navigation: {
                    nextEl: ".next-slide",
                    prevEl: ".prev-slide",
                }
            });

            this.slider.on('afterInit', () => {
                setTimeout(() => {
                    this.scrollItemToCenter(0);
                }, 2000)
            })

            this.slider.on('slideChange', () => {
                this.active = this.slider.realIndex;
                this.scrollItemToCenter(this.active);
            });
        },
        initSlider() {
            this.slider.init();
        },
        scrollItemToCenter(index) {
            // Container-Element abrufen
            const container = this.$refs.indices;
            const element = container.children[index + 1];

            if (!element) return;

            // Position des Elements innerhalb des Containers berechnen
            const scrollLeft = element.offsetLeft - container.offsetLeft -
                (container.clientWidth / 2) + (element.offsetWidth / 2);

            // Nur horizontales Scrollen innerhalb des Containers
            container.scrollTo({
                left: scrollLeft,
                behavior: 'smooth'
            });
        }
    }))
</script>
@endscript