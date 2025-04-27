<x-section>
    <section @class([
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
    ])>
        <div class="max-w-md">
            <x-headings>
                <x-slot name="tag">{{ $pagesSettings->vacancies_header }}</x-slot>
                {!! $pagesSettings->vacancies_subheader !!}
            </x-headings>
        </div>
        <div data-aos="fade" data-aos-delay="600" class="prose max-w-full">

            @if(!is_array($preparedText))
                {!! html_entity_decode( $preparedText ) !!}
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-12 w-full">
                    <div>{!! $preparedText['firstHalf'] !!}</div>
                    <div>{!! $preparedText['secondHalf'] !!}</div>
                </div>
            @endif
        </div>
    </section>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 mt-24">
        @foreach($vacancies as $index => $vacancy)
            <a href="{{ route('stellenanzeige', $vacancy) }}" wire:link  @class([
        "relative p-12 flex justify-center items-center border-logo",
        "border-b-2 last:border-b-transparent last:border-r-transparent",
        "sm:border-b-transparent" => $index > ($vacancies->count() - 3),
        "lg:border-b-transparent" => $index > ($vacancies->count() - 4),
        "sm:border-r-2" => ( $index + 1 ) % 2 !== 0,
        "lg:border-r-2" => ( $index + 1 ) % 3 !== 0,
        "lg:border-r-transparent" => ( $index + 1 ) % 3 == 0,
        ]) data-aos="fade" data-aos-delay="{{ $index * 100 }}">

                <div @class([
        "z-20 w-full aspect-square p-4 flex flex-col justify-between group hover:scale-110 transition-all duration-500",
        "bg-logo !text-white" => $index % 2 == 0 || $index == 0,
        "border border-logo" => $index % 2 !== 0 || $index !== 0,
        ])>
                    <h5 class="font-bold">{{ $vacancy->title }}</h5>

                    <div class="flex items-center justify-end">
                        <span @class(["group-hover:mr-2"])>Jetzt bewerben</span>
                        <svg class="size-4 invisible -translate-x-full group-hover:visible group-hover:translate-x-none group-hover:ml-2 transition-all duration-300"
                             data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25"></path>
                        </svg>
                    </div>
                </div>
        @endforeach
    </div>
</x-section>
