<div class="min-h-screen flex justify-center items-center">
    <div class="grid grid-cols-3 w-full -mt-[148px] relative z-10">
        <div class="h-screen bg-technik-200">
            <img src="{{ asset('technik-hero.jpg') }}" alt="" class="h-full min-w-full object-cover"/>
        </div>
        <div class="h-screen bg-white col-span-2">
            <div class="flex justify-center items-center w-full h-full">
                <div class="max-w-1/2 w-full" data-aos="fade" data-aos-delay="300">
                    <x-headings>
                        <x-slot name="tag">technik</x-slot>
                        Die SAAS Produkte von be real
                    </x-headings>
                    <div data-aos="fade" data-aos-delay="600" class="prose">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate dignissimos eveniet perspiciatis possimus voluptatem! Amet facere magnam odit ratione sit, tenetur totam! Enim ipsa maiores molestias odio officiis quaerat quisquam!
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute top-1/2 left-1/3 -translate-y-1/2 -translate-x-1/2 w-24 aspect-square rounded-full bg-white shadow-xl flex items-center justify-center">
            <div class="m-1.5 w-full aspect-square rounded-full border-4 border-technik-950 flex items-center justify-center">
                <svg class="size-16 text-technik-950" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"></path>
                    </svg>


{{--                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"></path>--}}
{{--                </svg>--}}
            </div>
        </div>
    </div>
</div>