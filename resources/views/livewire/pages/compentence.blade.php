<x-section>

    <div class="my-12">

        <x-headings>
            <x-slot name="tag">{{ $competence->keyword }}</x-slot>
            {!! $competence->name !!}
        </x-headings>


        <div class="w-full" data-aos="fade-up">
            <p class="!font-medium">{!! $competence->description !!}</p>
        </div>

        <div class="my-8" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ $competence->getFirstMediaUrl('titleimage', 'article_header') }}" class="w-full aspect-video object-cover" alt="{{ $competence->name }}"/>
        </div>

        <div class="w-full" data-aos="fade-up" data-aos-delay="200">
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
