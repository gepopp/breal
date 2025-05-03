<div class="my-8">
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach($this->realtyImages as $image_url)
                <div class="swiper-slide bg-technik-500/50 aspect-[4/3] flex items-center justify-center">
                    <div data-src="{{ $image_url }}" class="spotlight aspect-[4/3]" data-spotlight-title="{{ $realty->title }}">
                        <img src="{{ $image_url }}" alt="{{ $realty->title }}" class="object-cover w-auto h-full"/>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
    <script>
        new Swiper('.swiper', {
            slidesPerView: 1,
            autoplay: {
                delay: 3000,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        })
    </script>
</div>
@assets
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://rawcdn.githack.com/nextapps-de/spotlight/0.7.8/dist/spotlight.bundle.js"></script>
@endassets