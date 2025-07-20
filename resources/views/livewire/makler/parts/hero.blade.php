<div x-data="hero" class="w-full relative bg-makler-500">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            @foreach ($images as $image)
                <div class="swiper-slide">
                    <img src="{{ $image->getUrl() }}" srcset="{{ $image->getSrcset() }}" alt="bereal Makler Image" class="w-full object-cover" :style="{ height: `${heroHeight}px` }">
                </div>
            @endforeach
        </div>
    </div>
    <x-section class="bg-makler-500 dark:bg-makler-950 !text-white pt-36">
        <x-headings ondark="true">
            <x-slot name="tag">realtor</x-slot>
            Keine Immobilie gleicht der anderen und jede hat ihre eigene Geschichte.
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
                <h2 class="text-2xl font-logo font-bold">Immobiliensuche</h2>
                <form method="get" action="{{ route('makler.immobiliensuche') }}" class="grid grid-cols-2 gap-4 mt-4">
                    <flux:input name="filters[price_min]" type="number" label="Preis von"/>
                    <flux:input name="filters[price_max]" type="number" label="Preis bis"/>
                    <flux:input name="filters[space_min]" type="number" label="Fläche von"/>
                    <flux:input name="filters[space_max]" type="number" label="Fläche bis"/>
                    <flux:input name="filters[rooms_min]" type="number" label="Zimmer von"/>
                    <flux:input name="filters[rooms_max]" type="number" label="Zimmer bis"/>

                    <div>
                        <flux:radio.group name="filters.nutzungsart" label="Nutzungsart">
                            @foreach ($arten as $art )
                                @if($art->nutzungsart == 'WOHNEN')
                                    <flux:radio value="{{ $art->nutzungsart }}" label="{{ ucfirst( strtolower( $art->nutzungsart )) }}" checked />
                                @else
                                    <flux:radio value="{{ $art->nutzungsart }}" label="{{ ucfirst( strtolower( $art->nutzungsart )) }}"/>
                                @endif
                            @endforeach
                        </flux:radio.group>
                    </div>

                    <div>
                        <flux:radio.group name="filters.typ" label="Miete oder Kauf">
                            @foreach ($typen as $index => $art )
                                @if($art->vermarktungsart == 'kauf')
                                    <flux:radio value="{{ $art->vermarktungsart }}" label="{{ ucfirst( strtolower( $art->vermarktungsart )) }}" checked/>
                                @else
                                    <flux:radio value="{{ $art->vermarktungsart }}" label="{{ ucfirst( strtolower( $art->vermarktungsart )) }}"/>
                                @endif

                            @endforeach
                        </flux:radio.group>
                    </div>
                    <div class="mt-4 col-span-2">
                        <x-button type="submit" class="w-full">suchen</x-button>
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
        init() {
            this.calcHeight();

            var heroswiper = new Swiper('.hero-swiper', {
                slidesPerView: 1,
                effect: 'fade',
                autoplay: {
                    delay: 3000,
                },
                speed: 1500,
            });

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
