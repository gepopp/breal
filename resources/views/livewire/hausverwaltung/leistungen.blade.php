<x-section>
    <section @class([
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('makler.*'),
    ])>
        <div class="max-w-md w-full">
            <x-headings>
                <x-slot name="tag">{{ $pagesSettings->leistungen_header }}</x-slot>
                {{ $pagesSettings->leistungen_subheader }}
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
        @foreach($leistungen as $index => $competence)
            <div @class([
        "relative p-12 flex justify-center items-center border-logo",
        "border-b-2 last:border-b-transparent last:border-r-transparent",
        "sm:border-b-transparent" => $index > ($leistungen->count() - 3),
        "lg:border-b-transparent" => $index > ($leistungen->count() - 4),
        "sm:border-r-2" => ( $index + 1 ) % 2 !== 0,
        "lg:border-r-2" => ( $index + 1 ) % 3 !== 0,
        "lg:border-r-transparent" => ( $index + 1 ) % 3 == 0,
        ]) data-aos="fade" data-aos-delay="{{ $index * 100 }}">


                @php
                    $firstLetter = \Illuminate\Support\Str::limit($competence->name, 1, '');
                @endphp

                <div data-aos="fade-up" data-aos-delay="{{ $index * 200 }}" class="absolute top-2 left-2">
                    <div class="relative opacity-50 z-10">
                        <span @class(["text-8xl font-logo text-transparent bg-clip-text bg-center"]) style="background-image: url({{ $competence->getFirstMediaUrl('titleimage', 'thumb') }})">{{ $firstLetter }}</span>
                    </div>
                </div>

                <div @class(["z-20"])>
                    <h5 class="font-bold line-clamp-1">{{ $competence->name }}</h5>
                    <p class="!text-sm line-clamp-5">{{ $competence->description }}</p>

                    @php
                        $company = strtolower($competence->company->value);
                        $company = $company == 'makler' ? 'immobilien' : $company;
                    @endphp

                    <a href="{{ route(  $company . '.leistung', ['competence' => $competence ] ) }}" class="mt-4 text-right w-full block">weiterlesen</a>
                </div>

            </div>
        @endforeach
    </div>

</x-section>
