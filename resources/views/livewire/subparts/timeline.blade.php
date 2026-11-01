<div class="mt-12 relative max-w-screen">
    <div x-data="timeline" class="mt-12 md:mt-24" x-intersect.once="initSlider" >

        <div class="relative w-3/4 lg:w-1/2 mb-4 ml-auto mb-8">
            <div x-ref="indices"
                 class="relative flex items-center overflow-x-scroll scrollbar scrollbar-none">
                <span class="w-full h-[2px] bg-logo dark:bg-white shrink-0 mx-2"></span>
                @foreach($timeline as $key => $entry)
                    <span class="flex items-center">
                     <button type="button" x-on:click="goto({{ $key }})"
                           x-bind:aria-current="active == {{ $key }} ? 'true' : null"
                           class="font-logo shrink-0 mx-2 rounded transition-all duration-300 cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-logo-500"
                           :class="active == {{ $key }} ? 'bg-logo dark:bg-white dark:text-logo-950 text-white px-1.5 text-xl scale-110' : 'bg-transparent text-logo dark:text-white text-base'">
                         {{ $entry->year }}
                     </button>
                    @if($key < count($timeline) - 1 )
                            <span class="w-12 h-[2px] bg-logo dark:bg-white shrink-0 mx-2"></span>
                        @endif
                </span>
                @endforeach
                <span class="w-full h-[2px] bg-logo shrink-0 mx-2"></span>

            </div>
            <div class="absolute pointer-events-none inset-0 bg-linear-to-r from-white dark:from-logo-950 via-transparent to-white dark:to-logo-950"></div>

        </div>


        <div class="relative max-w-full">
            <div class="swiper max-w-screen pb-14" x-ref="timelineswiper" data-pause-control="custom">
                <button type="button" x-on:click="toggleAutoplay()" x-bind:aria-label="playing ? @js(__('carousel.pause')) : @js(__('carousel.play'))"
                        class="absolute bottom-0 right-4 z-[10000] size-10 rounded-full bg-logo text-white shadow flex items-center justify-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-logo-500">
                    <svg x-show="playing" aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
                    <svg x-show="!playing" x-cloak aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                </button>
                <div class="swiper-wrapper w-full">
                    @foreach($timeline as $key => $entry)
                        <div class="swiper-slide max-w-screen bg-transparent">
                            <div class="inset-0 flex justify-center items-center px-4 sm:px-8">
                                <div class="flex md:max-w-3/4 relative">
                                    <div class="w-1/3 lg:w-1/2">
                                        <x-media-image :media="$entry->getFirstMedia('*')" sizes="(min-width: 1024px) 25vw, 33vw"
                                             class="aspect-square w-full object-cover" alt="{{ $entry->title }}"/>
                                    </div>
                                    <div class="w-2/3 lg:w-1/2 flex flex-col justify-center ml-4 mt-6">
                                        <div class="relative">
                                            <h3 class="font-bold text-sm md:text-xl text-logo dark:text-white">{{ $entry->title }}</h3>
                                            <div class="hidden sm:block absolute top-0 -mt-6 left-0 -ml-20 w-36 h-[3px] bg-logo dark:bg-white text-right">
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
            <div class="cursor-pointer absolute top-1/2 left-0 w-6 md:w-12 -translate-x-1/2 -translate-y-1/2  aspect-square rounded-full bg-logo text-white flex justify-center items-center shadow-lg">
                <button type="button" class="relative prev-slide !z-[9999] cursor-pointer" aria-label="{{ __('carousel.previous') }}">
                    <svg class="size-5 md:size-10" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"></path>
                    </svg>
                </button>
            </div>

            <button type="button" aria-label="{{ __('carousel.next') }}" class="next-slide cursor-pointer absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-6 md:w-12 right-0 aspect-square rounded-full bg-logo text-white flex justify-center items-center z-[9999] shadow-lg">
                <svg class="size-5 md:size-10" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
                </svg>
            </button>

        </div>
    </div>
</div>
@script
<script>
    Alpine.data('timeline', () => ({
        slider: null,
        active: 0,
        playing: true,
        toggleAutoplay() {
            this.playing = !this.playing;
            this.playing ? this.slider.autoplay.start() : this.slider.autoplay.stop();
        },
        initSlider() {
            this.playing = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            this.slider = new Swiper(this.$refs.timelineswiper, {
                loop: true,
                slidesPerView: 1,
                speed: 800,
                spaceBetween: 30,
                autoplay: {
                    delay: {{ app( \App\Settings\LandingpageHausverwaltung::class )->timeline_speed }},
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                keyboard: {
                    enabled: true,
                },
                navigation: {
                    nextEl: ".next-slide",
                    prevEl: ".prev-slide",
                }
            });

            if (!this.playing) {
                this.slider.autoplay.stop();
            }

            this.slider.on('init', () => {
                setTimeout(() => {
                    this.scrollItemToCenter(0);
                }, 100)
            })


            this.slider.on('slideChange', () => {
                this.active = this.slider.realIndex;
                this.scrollItemToCenter(this.active);
            });
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
        },
        goto(index) {
            this.slider.slideToLoop(index);
        }
    }))
</script>
@endscript