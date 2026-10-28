<x-section>

    <div class="my-12">

        <x-headings level="1">
            <x-slot name="tag">informed</x-slot>
            {!! $faq->question !!}
        </x-headings>

        @if($faq->hasMedia('*'))
            <x-media-image :media="$faq->getFirstMedia('*')" alt="{{ strip_tags($faq->question) }}"/>
        @endif


        @if($faq->stand)
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('faq.as_of', ['date' => $faq->stand->format('d.m.Y')]) }}</p>
        @endif

        <div class="flex flex-col md:flex-row mt-12">
            <div class="w-full md:w-3/4 prose" data-aos="fade-up">
                {!! $faq->answer !!}
            </div>
        </div>
    </div>


    <livewire:contact-form/>
</x-section>
