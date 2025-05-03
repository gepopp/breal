<div class="my-8">
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach($this->realtyImages as $image_url)
                <div class="swiper-slide">
                    <img src="{{ $image_url }}" alt="{{ $realty->title }}"/>
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
@endassets