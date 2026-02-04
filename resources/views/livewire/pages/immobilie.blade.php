<x-section>
    <div class="w-2/3">
        <h5 class="text-opacity-50 mb-4">
            {{ $realty->strasse }} {{ $realty->hausnummer }}, {{ $realty->plz }} {{ $realty->ort }}
            | {{ $realty->nutzungsart }}
            | Objektnummer: {{ $realty->objektnr_extern }}
        </h5>
        <h1 class="text-2xl font-bold">{{ $realty->title }}</h1>
    </div>

    <div class="flex flex-col lg:flex-row lg:space-x-8">
        <div class="w-full lg:w-2/3">

            <livewire:subparts.realty-slider :realty="$realty"/>

            @if(array_key_exists('freitexte', $realty->data) && array_key_exists('lage', $realty->data['freitexte']))
                <div class="prose">
                    <h3>{{ __('realty.location') }}</h3>
                    {!! $realty['data']['freitexte']['lage'] !!}
                </div>
            @endif

            @if(array_key_exists('geo', $realty->data) && array_key_exists('geokoordinaten', $realty->data['geo']))
                <livewire:realty.map :$realty/>
            @endif

            <div class="prose">
                <h3>{{ __('realty.description') }}</h3>
                {!! $realty->beschreibung !!}
            </div>
        </div>
        <div class="w-full lg:w-1/3 md:mt-8">

            <livewire:realty.contact :$realty/>

            @if(array_key_exists('ausstattung', $realty->data))
                <livewire:realty.price :$realty/>
            @endif

            <div class="pt-4 mt-4 border-t border-gray-200">
                <h3>{{ __('realty.key_data') }}</h3>

                <livewire:realty.spaces :spaces="$realty->data['flaechen']"/>


                <ul class="!mt-8">
                    <li class="!ml-0 flex justify-between">
                        <span>{{ __('realty.usage_type') }}</span>
                        <span>{{ ucfirst(strtolower( $realty->nutzungsart)) }}</span>
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
                                <span>{{
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
