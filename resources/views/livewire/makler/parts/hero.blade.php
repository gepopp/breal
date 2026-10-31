<div x-data="hero" class="w-full relative bg-makler-500">
    <button type="button" x-on:click="toggleAutoplay()" x-bind:aria-label="playing ? @js(__('carousel.pause')) : @js(__('carousel.play'))"
            class="absolute top-4 right-4 z-[10000] size-10 rounded-full bg-white/90 text-logo-950 shadow flex items-center justify-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-logo-500">
        <svg x-show="playing" aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>
        <svg x-show="!playing" x-cloak aria-hidden="true" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
    </button>
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            @foreach ($images as $image)
                <div class="swiper-slide">
                    <x-media-image :media="$image" :priority="$loop->first" alt="{{ __('makler.hero_image_alt') }}" class="w-full object-cover" x-bind:style="{ height: `${heroHeight}px` }"/>
                </div>
            @endforeach
        </div>
    </div>
    <x-section class="bg-makler-500 dark:bg-makler-950 !text-white pt-36">
        <x-headings level="1" ondark="true">
            <x-slot name="tag">realtor</x-slot>
            {{ $settings->intro_subtitle }}
        </x-headings>
        <div data-aos="fade" data-aos-delay="200">
            @if(!is_array($preparedText))
                {!! html_entity_decode( $preparedText ) !!}
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-12 w-full mb-24">
                    <div>{!! $preparedText['firstHalf'] !!}</div>
                    <div>{!! $preparedText['secondHalf'] !!}</div>
                </div>
            @endif
        </div>
    </x-section>
    @if($realEstates->count())
        <div class="absolute top-0 md:right-[5%] inset-0 flex items-center justify-end px-4">
            <div class="relative  p-6 bg-white/50 dark:bg-makler-950/50 backdrop-blur-sm  -translate-y-1/4 rounded-xl z-20 shadow-lg">
                <h2 class="text-2xl font-logo font-bold">{{ __('makler.search.title') }}</h2>
                <form method="get" action="{{ route('makler.immobiliensuche') }}" class="grid grid-cols-2 gap-4 mt-4">
                    <flux:input name="filters[price_min]" type="number" label="{{ __('makler.search.price_from') }}"/>
                    <flux:input name="filters[price_max]" type="number" label="{{ __('makler.search.price_to') }}"/>
                    <flux:input name="filters[space_min]" type="number" label="{{ __('makler.search.space_from') }}"/>
                    <flux:input name="filters[space_max]" type="number" label="{{ __('makler.search.space_to') }}"/>
                    <flux:input name="filters[rooms_min]" type="number" label="{{ __('makler.search.rooms_from') }}"/>
                    <flux:input name="filters[rooms_max]" type="number" label="{{ __('makler.search.rooms_to') }}"/>

                    <div>
                        <flux:radio.group name="filters.nutzungsart" label="{{ __('makler.search.usage_type') }}">
                            @foreach ($arten as $art )
                                @php
                                    $usageLabel = match(strtoupper($art->nutzungsart)) {
                                        'WOHNEN' => __('makler.usage_type.wohnen'),
                                        'GEWERBE' => __('makler.usage_type.gewerbe'),
                                        default => ucfirst(strtolower($art->nutzungsart))
                                    };
                                @endphp
                                @if($art->nutzungsart == 'WOHNEN')
                                    <flux:radio value="{{ $art->nutzungsart }}" label="{{ $usageLabel }}" checked />
                                @else
                                    <flux:radio value="{{ $art->nutzungsart }}" label="{{ $usageLabel }}"/>
                                @endif
                            @endforeach
                        </flux:radio.group>
                    </div>

                    <div>
                        <flux:radio.group name="filters.typ" label="{{ __('makler.search.rent_or_buy') }}">
                            @foreach ($typen as $index => $art )
                                @php
                                    $typeLabel = match(strtolower($art->vermarktungsart)) {
                                        'miete' => __('makler.marketing_type.miete'),
                                        'kauf' => __('makler.marketing_type.kauf'),
                                        default => ucfirst(strtolower($art->vermarktungsart))
                                    };
                                @endphp
                                @if($art->vermarktungsart == 'kauf')
                                    <flux:radio value="{{ $art->vermarktungsart }}" label="{{ $typeLabel }}" checked/>
                                @else
                                    <flux:radio value="{{ $art->vermarktungsart }}" label="{{ $typeLabel }}"/>
                                @endif

                            @endforeach
                        </flux:radio.group>
                    </div>
                    <div class="mt-4 col-span-2">
                        <x-button type="submit" class="w-full">{{ __('makler.search.search_button') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@script
<script>
    Alpine.data('hero', () => ({
        heroHeight: 0,
        playing: true,
        swiper: null,
        toggleAutoplay() {
            this.playing = !this.playing;
            this.playing ? this.swiper.autoplay.start() : this.swiper.autoplay.stop();
        },
        init() {
            this.calcHeight();

            this.playing = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            this.swiper = new Swiper('.hero-swiper', {
                slidesPerView: 1,
                effect: 'fade',
                autoplay: { delay: 3000 },
                speed: 1500,
            });
            if (!this.playing) {
                this.swiper.autoplay.stop();
            }

            window.addEventListener('resize', this.calcHeight);
        },
        calcHeight() {
            var navbar = document.querySelector('#navbar').offsetHeight;
            var screenHeight = window.innerHeight;
            this.heroHeight = ((screenHeight - navbar) / 4) * 3;
        }
    }))
</script>
@endscript
@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endassets
