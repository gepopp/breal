<x-section>

    <div class="my-12">

        <x-headings>
            <x-slot name="tag">{{ $competence->keyword }}</x-slot>
            {!! $competence->name !!}
        </x-headings>


        <div class="w-full" data-aos="fade-up">
            <p class="!font-medium">{!! $competence->description !!}</p>
        </div>



        <div class="w-full mt-8" data-aos="fade-up" data-aos-delay="200">
            <div class="w-full mb-8 sm:mb-0 sm:w-1/2 float-right ml-8 relative">
                <img src="{{ $competence->getFirstMediaUrl('titleimage', 'article_header') }}" class="w-full aspect-video object-cover" alt="{{ $competence->name }}"/>
                <div class="absolute bottom-0 left-0 w-[20%] h-full hidden sm:block">
                    <svg @class(['text-white dark:text-logo-950 h-full w-auto z-[9999] relative']) fill="currentColor" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 85.3 501">
                        <polygon points="0 501 0 501 0 0 85.3 0 0 501"/>
                    </svg>
                </div>
            </div>
            {!! $competence->body !!}
        </div>

        <div class="mt-12 flex justify-center">
            @php
                $company = strtolower($competence->company->value);
            @endphp
            <x-button href="{{ route( $company == 'makler' ? 'immobilien.leistungen' : $company . '.leistungen' ) }}" wire:navigate data-aos="fade-up" data-aos-delay="500">Alle Leistungen</x-button>
        </div>
    </div>
</x-section>
