<x-section class="bg-white dark:bg-makler-950">

    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-24">
        <div data-aos="fade-up">
            <img src="{{ $image->getUrl() }}" alt="{{ $settings->about_image_alt }}" class="rounded-xl md:-translate-y-[70px]"/>
        </div>
        <div>
            <h2 data-aos="fade-up" data-aos-delay="100" class="text-2xl font-bold text-makler-950 dark:text-white">{{ $settings->about_heading }}</h2>
            <div data-aos="fade-up" data-aos-delay="200" class="prose mt-8">
                {!! $settings->about_text !!}
            </div>
        </div>
    </div>
</x-section>