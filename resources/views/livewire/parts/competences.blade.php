<x-section>
    <div @class([ 'max-w-screen w-full', 'md:max-w-sm lg:max-w-lg' => !is_array($preparedText)])>
        <x-headings>
            <x-slot name="tag">{{ $header }}</x-slot>
            {!! $subheader !!}
        </x-headings>
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
    </div>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 mt-24">
        @foreach($competences as $index => $competence)
            <div @class([
        "relative p-12 flex justify-center items-center border-logo",
        "border-b-2 last:border-b-transparent last:border-r-transparent",
        "sm:border-b-transparent" => $index > ($competences->count() - 3),
        "lg:border-b-transparent" => $index > ($competences->count() - 4),
        "sm:border-r-2" => ( $index + 1 ) % 2 !== 0,
        "lg:border-r-2" => ( $index + 1 ) % 3 !== 0,
        "lg:border-r-transparent" => ( $index + 1 ) % 3 == 0,
        ]) data-aos="fade" data-aos-delay="{{ $index * 100 }}">


                @php
                    $firstLetter = \Illuminate\Support\Str::limit($competence->name, 1, '');
                    $files = \Illuminate\Support\Facades\File::allFiles(public_path('homes'));
                    $images = [];
                    foreach($files as $file){
                        $images[] = asset('homes/' . $file->getFilename());
                    }
                @endphp

                <div data-aos="fade-up" data-aos-delay="{{ $index * 200 }}" class="absolute top-2 left-2">
                    <div class="relative opacity-50 z-10">
                        <span @class(["text-8xl font-logo text-transparent bg-clip-text bg-center"]) style="background-image: url({{ \Illuminate\Support\Arr::random($images) }})">{{ $firstLetter }}</span>
                    </div>
                </div>

                <div @class(["z-20"])>
                    <h5 class="font-bold line-clamp-1">{{ $competence->name }}</h5>
                    <p class="!text-sm line-clamp-5">{{ $competence->description }}</p>
                </div>

            </div>
        @endforeach
    </div>
</x-section>
