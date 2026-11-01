<x-section>
    <div class="w-2/3">
        <p class="text-opacity-50 mb-4">
            <span {!! \App\Services\ExternalText::langAttribute() !!}>{{ $realty->strasse }} {{ $realty->hausnummer }}, {{ $realty->plz }} {{ $realty->ort }}</span>
            | {{ \App\Services\ExternalText::usageLabel($realty->nutzungsart) }}
            | {{ __('realty.object_number') }}: {{ $realty->objektnr_extern }}
        </p>
        <h1 class="text-2xl font-bold" @if(app()->getLocale() !== 'de') lang="de" @endif>{{ $realty->title }}</h1>
    </div>

    <div class="flex flex-col lg:flex-row lg:space-x-8">
        <div class="w-full lg:w-2/3">

            <livewire:subparts.realty-slider :realty="$realty"/>

            @if(array_key_exists('freitexte', $realty->data) && array_key_exists('lage', $realty->data['freitexte']))
                <div class="prose">
                    <h2>{{ __('realty.location') }}</h2>
                    <div @if(app()->getLocale() !== 'de') lang="de" @endif>
                        {!! \App\Services\HtmlLists::demoteH1($realty['data']['freitexte']['lage']) !!}
                    </div>
                </div>
            @endif

            @if(array_key_exists('geo', $realty->data) && array_key_exists('geokoordinaten', $realty->data['geo']))
                <livewire:realty.map :$realty/>
            @endif

            <div class="prose">
                <h2>{{ __('realty.description') }}</h2>
                <div @if(app()->getLocale() !== 'de') lang="de" @endif>
                    {!! \App\Services\HtmlLists::demoteH1((string) $realty->beschreibung) !!}
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/3 md:mt-8">

            <livewire:realty.contact :$realty/>

            @if(array_key_exists('ausstattung', $realty->data))
                <livewire:realty.price :$realty/>
            @endif

            <div class="pt-4 mt-4 border-t border-gray-200">
                <h2>{{ __('realty.key_data') }}</h2>

                <livewire:realty.spaces :spaces="$realty->data['flaechen']"/>


                <ul class="!mt-8">
                    <li class="!ml-0 flex justify-between">
                        <span>{{ __('realty.usage_type') }}</span>
                        <span>{{ \App\Services\ExternalText::usageLabel($realty->nutzungsart) }}</span>
                    </li>
                    @if($realty->vermarktungsart == 'miete')
                        @if(array_key_exists('verfuegbar_ab', $data['verwaltung_objekt']))
                            <li class="!ml-0 flex justify-between">
                                <span>{{ __('realty.available_from') }}</span>
                                <span>{{ $data['verwaltung_objekt']['verfuegbar_ab'] }}</span>
                            </li>
                        @endif
                        @if(array_key_exists('max_mietdauer', $data['verwaltung_objekt']))
                            <li class="!ml-0 flex justify-between">
                                <span>{{ __('realty.rental_duration') }}</span>
                                <span @if(! is_numeric($data['verwaltung_objekt']['max_mietdauer'])) {!! \App\Services\ExternalText::langAttribute() !!} @endif>{{
                                        is_numeric( $data['verwaltung_objekt']['max_mietdauer'] )
                                        ? $data['verwaltung_objekt']['max_mietdauer'] . ' ' . __('realty.years')
                                        : $data['verwaltung_objekt']['max_mietdauer']
                                    }}</span>
                            </li>
                        @endif
                    @endif
                </ul>

            </div>

            @if(array_key_exists('zustand_angaben', $realty->data))
                <livewire:realty.shape :$realty/>
            @endif

            @if(array_key_exists('ausstattung', $realty->data))
                <livewire:realty.features :$realty/>
            @endif

        </div>
    </div>
</x-section>
