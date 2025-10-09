<div class="mt-8">

    @if($withIntro)
        <div @class("pb-24 border-b border-gray-200")>
            <h2 @class([
        "font-bold text-3xl sm:text-5xl lg:text-7xl",
        "text-logo-950" => !Route::is('technik.*', 'makler.*'),
        "text-technik-950" => Route::is('technik.*'),
        "text-makler-950" => Route::is('makler.*'),
    ]) data-aos="fade">{{ $pagesSettings->contactpersons_heading }}</h2>
            <p data-aos="fade" data-aos-delay="500">
                {{ $pagesSettings->contactpersons_introtext }}
            </p>
        </div>
    @endif

    <div @class(["mt-24" => $withIntro])>
        @foreach($departments as $department)
            @if($department->department?->count > 0)
                continue;
            @endif
            <div @class(['mb-12']) data-aos="fade">
                <h3 @class([
                    "uppercase font-bold text-3xl",
                    "text-logo-950 dark:text-white" => !Route::is('technik.*', 'makler.*'),
                    "text-technik-950" => Route::is('technik.*'),
                    "text-makler-950" => Route::is('makler.*'),
                ])>{{ $department->name }}</h3>


                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-12 my-12">
                    @foreach($department->contacts as $contact)
                        <livewire:contact-person :contactperson="$contact"/>
                    @endforeach
                </div>

                @foreach($department->sub as $sub)
                    <div @class(["my-8"])  data-aos="fade">
                        <h5 @class([
                            "border px-6 py-1 rounded-full inline text-sm opacity-50",
                            "text-logo-950 border-logo-800 bg-logo-100/50 dark:text-white" => !Route::is('technik.*', 'makler.*'),
                            "text-technik-950 border-technik-800 bg-technik-100/50" => Route::is('technik.*'),
                            "text-makler-950 border-makler-800 bg-makler-100/50" => Route::is('makler.*'),
                            ])>{{ $sub->name }}</h5>

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-12 my-12">
                            @foreach($sub->contacts as $contact)
                                <livewire:contact-person :contactperson="$contact"/>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        @endforeach

    </div>

</div>
