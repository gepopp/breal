<div>
    <div>
        <div x-data="{
        init(){
             if(window.innerWidth > 767 && $refs.wrapper){
                 var height = $refs.textCol.clientHeight;
                 $refs.wrapper.style.height = `${height}px`;
                 setTimeout(() => $dispatch('setheight', height), 500);
            }


            window.addEventListener('resize', () => {
                 if(window.innerWidth > 767 && $refs.wrapper){
                     var height = $refs.textCol.clientHeight;
                     $refs.wrapper.style.height = `${height}px`;
                     setTimeout(() => $dispatch('setheight', height), 500);
                }
            })
        }
    }"
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
            <div @class(['relative overflow-hidden my-8 md:my-0'])>

                @if($media?->count() == 1)
                    @php
                        $media = $media->first();
                    @endphp
                    <img src="{{ $media?->getUrl('layout') }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $settings->text_image_alt }}" data-aos="fade"
                         data-aos-delay="600" class="h-full w-auto object-cover"/>

                @elseif($media?->count() > 1)

                    <div x-ref="swiper" class="swiper swiperSecond min-h-full">
                        <div x-ref="wrapper" class="swiper-wrapper min-h-full flex-1">
                            @foreach($media as $image)
                                <div class="swiper-slide min-h-full">
                                    <img scr="{{ $image->getUrl('slider') }}" alt="{{ $settings->text_image_alt }}" srcset="{{ $image->getSrcset() }}" class="h-full w-auto object-cover"/>
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
                    </script>
                @endif

                <div class="absolute top-0 left-0 h-full hidden md:block flex justify-end">
                    <svg @class(['text-white dark:text-logo-950 h-full w-auto ml-auto -mr-px relative z-[9999]']) fill="currentColor"
                         xmlns="http://www.w3.org/2000/svg"
                         version="1.1" viewBox="0 0 85.3 501">
                        <polygon class="st0" points="85.3 501 0 501 0 0 0 0 85.3 501"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
