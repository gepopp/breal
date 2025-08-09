<x-section>

    <div class="my-12">

        <x-headings>
            <x-slot name="tag">informed</x-slot>
            {!! $faq->question !!}
        </x-headings>


        <div class="flex flex-col md:flex-row mt-12">
            <div class="w-full md:w-3/4 prose" data-aos="fade-up">
                {!! $faq->answer !!}
            </div>
        </div>
    </div>


    <livewire:contact-form/>
</x-section>
