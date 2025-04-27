<div class="w-full aspect-video relative">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            @foreach ($images as $image)
                <div class="swiper-slide">
                    <img src="{{ $image->getUrl() }}" alt="">
                </div>
            @endforeach
        </div>
    </div>
    <script>
        var heroswiper = new Swiper('.hero-swiper', {
            slidesPerView: 1,
            effect: 'fade',
            autoplay: {
                delay: 3000,
            },
            speed: 1500,
        });
    </script>
    <div class="absolute top-0 right-[5%] inset-0 flex items-center justify-end">
        <div class="relative  p-6 bg-white/50 backdrop-blur-sm   rounded-xl z-20">
            <h2 class="text-2xl font-logo font-bold">Immobiliensuche</h2>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <flux:input type="number" label="Preis von"/>
                <flux:input type="number" label="Preis bis"/>
                <flux:input type="number" label="Fläche von"/>
                <flux:input type="number" label="Fläche bis"/>
                <flux:input type="number" label="Zimmer von"/>
                <flux:input type="number" label="Zimmer bis"/>

                <h5 class="col-span-2 font-bold text-sm">Suchen in</h5>

                <x-button class="w-full bg-white">Miete</x-button>
                <x-button class="w-full">Kauf</x-button>
            </div>
            <div class="mt-4">
                <x-button class="w-full">büro & Gewerbe</x-button>
            </div>

        </div>
    </div>
</div>
@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endassets
