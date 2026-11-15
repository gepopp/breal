<div class="realty-gallery my-8" id="realty-gallery-{{ $this->getId() }}">
    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper realty-gallery-main">
        <div class="swiper-wrapper">
            @foreach($this->realtyImages as $image)
                <div class="swiper-slide bg-technik-500/50 aspect-[4/3] flex items-center justify-center">
                    <div data-src="{{ $image['url'] }}" class="spotlight aspect-[4/3]" data-spotlight-title="{{ $realty->title }}">
                        @php($slideSrcset = \App\Services\RealtyImage::srcset($image['url'], [480, 800, 1280]))
                        <img src="{{ \App\Services\RealtyImage::url($image['url'], 800) ?? $image['url'] }}"
                             @if($slideSrcset) srcset="{{ $slideSrcset }}" sizes="(min-width: 1024px) 700px, 100vw" @endif
                             width="1280" height="720"
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}" @if($loop->first) fetchpriority="high" @endif decoding="async"
                             alt="{{ $image['title'] ?? __('realty.slider.photo', ['index' => $loop->iteration, 'count' => $loop->count]) }}" class="object-cover w-auto h-full select-none"/>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    {{-- The thumbnails repeat the photos above and cannot be reached by keyboard, so they stay out of the accessibility tree. --}}
    <div thumbsSlider="" class="swiper realty-gallery-thumbs" aria-hidden="true">
        <div class="swiper-wrapper">
            @foreach($this->realtyImages as $image)
                <div class="swiper-slide">
                    @php($thumbSrcset = \App\Services\RealtyImage::srcset($image['url'], [160, 320]))
                    <img src="{{ \App\Services\RealtyImage::url($image['url'], 160) ?? $image['url'] }}"
                         @if($thumbSrcset) srcset="{{ $thumbSrcset }}" sizes="160px" @endif
                         width="160" height="160" loading="lazy" decoding="async"
                         alt="" class="object-cover aspect-square select-none"/>
                </div>
            @endforeach
        </div>
    </div>


    <script>
    onSwiper(() => {
        var gallery = document.getElementById(@js('realty-gallery-'.$this->getId()));
        var swiper = new Swiper(gallery.querySelector(".realty-gallery-thumbs"), {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(gallery.querySelector(".realty-gallery-main"), {
            spaceBetween: 10,
            navigation: {
                nextEl: gallery.querySelector(".swiper-button-next"),
                prevEl: gallery.querySelector(".swiper-button-prev"),
            },
            a11y: {
                prevSlideMessage: @js(__('realty.slider.previous')),
                nextSlideMessage: @js(__('realty.slider.next')),
                firstSlideMessage: @js(__('realty.slider.first')),
                lastSlideMessage: @js(__('realty.slider.last')),
            },
            thumbs: {
                swiper: swiper,
            },
        });
    });
</script>
</div>
