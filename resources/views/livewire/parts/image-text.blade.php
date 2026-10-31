<div>
    <div>
        <div
                x-data="imageTextHeight"
                x-ref="textCol"
                @class([
        "mx-auto grid grid-cols-1 md:grid-cols-2 relative min-h-[70vh] w-full mb-24 md:mb-0"
        ])>
            <div @class(['flex justify-center items-center md:min-h-[70vh]'])>
                <div @class(['md:max-w-sm lg:max-w-lg px-4 lg:my-24'])>
                    <div data-aos="fade" class="prose">
                        {!! $settings->text !!}
                    </div>
                </div>
            </div>
            <div @class(['order-first relative overflow-hidden my-8 md:my-0'])>

                @if($media?->count() == 1)
                    @php
                        $media = $media->first();
                    @endphp
                    <x-media-image :media="$media" sizes="(min-width: 768px) 50vw, 100vw" alt="{{ $settings->text_image_alt }}" data-aos="fade"
                 data-aos-delay="600" class="h-full w-auto object-cover"/>

                @elseif($media?->count() > 1)

                    <div x-ref="swiper" class="swiper swiperSecond min-h-full">
                        <button type="button" x-on:click="toggleAutoplay()" x-bind:aria-label="playing ? @js(__('carousel.pause')) : @js(__('carousel.play'))"
            class="absolute bottom-4 left-4 z-[10000] size-10 rounded-full bg-white/90 text-logo-950 shadow flex items-center justify-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-logo-500">
        <svg x-show="playing" aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
        <svg x-show="!playing" x-cloak aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
    </button>

                        <div x-ref="wrapper" class="swiper-wrapper min-h-full flex-1">
                            @foreach($media as $image)
                                <div class="swiper-slide min-h-full">
                                    <x-media-image :media="$image" sizes="(min-width: 768px) 50vw, 100vw" alt="{{ $settings->text_image_alt }}" class="h-full w-auto object-cover"/>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <script>
                        const swiperSecond = new Swiper('.swiperSecond', {
                            // Optional parameters
                            loop: true,
                            slidesPerView: 1,
                            speed: 800,
                            autoplay: {
                                delay: 2500
                            }
                        });
                        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                            swiperSecond.autoplay.stop();
                        }
                    </script>
                @endif

                <div class="absolute top-0 right-0 h-full hidden md:block flex justify-end">
                    <svg @class(['text-white dark:text-logo-950 h-full w-auto ml-auto -mr-px relative z-[9999]']) fill="currentColor"
                         xmlns="http://www.w3.org/2000/svg"
                         version="1.1" viewBox="0 0 85.3 501">
                        <polygon class="st0" points="85.3 0 85.3 0 85.3 501 0 501 85.3 0"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    Alpine.data('imageTextHeight', () => ({
        playing: !window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        toggleAutoplay() {
            const swiper = this.$refs.swiper.swiper;
            this.playing = !this.playing;
            this.playing ? swiper.autoplay.start() : swiper.autoplay.stop();
        },
        init() {
            if (window.innerWidth > 767 && this.$refs.wrapper) {
                const height = this.$refs.textCol.clientHeight;
                this.$refs.wrapper.style.height = `${height}px`;
                setTimeout(() => this.$dispatch('setheight', height), 500);
            }

            window.addEventListener('resize', () => {
                if (window.innerWidth > 767 && this.$refs.wrapper) {
                    const height = this.$refs.textCol.clientHeight;
                    this.$refs.wrapper.style.height = `${height}px`;
                    setTimeout(() => this.$dispatch('setheight', height), 500);
                }
            });
        }
    }));
</script>
@endscript
