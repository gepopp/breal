<div>
    <ul>
        @if(array_key_exists('baujahr', $data))
            <li class="!ml-0 flex justify-between">
                <span>{{ __('realty.year_built') }}</span>
                <span>{{ $data['baujahr'] }}</span>
            </li>
        @endif
    </ul>
    @if(array_key_exists('energiepass', $data))
        <div class="pt-4 mt-4 border-t border-gray-200">

            <h3>{{ __('realty.energy_certificate') }}</h3>
            <ul>
                @if(array_key_exists('hwbwert', $data['energiepass']) && array_key_exists('hwbklasse', $data['energiepass']))
                    <li class="!ml-0 flex justify-between">
                        <span>{{ __('realty.hwb') }}</span>
                        <span class="flex items-center space-x-1">
                        <span>{{ $data['energiepass']['hwbwert'] }} kWh/m²a</span>
                        {!! \App\Enums\EnergieausweissEnum::{$data['energiepass']['hwbklasse']}->getBadge() !!}
                    </span>
                    </li>
                @endif
                @if(array_key_exists('fgeewert', $data['energiepass']) && array_key_exists('fgeeklasse', $data['energiepass']))
                    <li class="!ml-0 flex justify-between">
                        <span>fGEE</span>
                        <span class="flex items-center space-x-1">
                        <span>{{ $data['energiepass']['fgeewert'] }}</span>
                        {!! \App\Enums\EnergieausweissEnum::{$data['energiepass']['fgeeklasse']}->getBadge() !!}
                    </span>
                    </li>
                @endif


                @if(array_key_exists('heizungsart', $ausstattung))
                    <li class="!ml-0 flex justify-between">
                        <span>{{ __('realty.heating') }}</span>
                        <span>
                                @foreach($ausstattung['heizungsart']['@attributes'] as $key => $heizung)
                                @if($heizung == 1)
                                    {{  ucfirst( strtolower( $key )) }}
                                @endif
                            @endforeach
                            </span>
                    </li>
                @endif
                @if(array_key_exists('befeuerung', $ausstattung))
                    <li class="!ml-0 flex justify-between">
                        <span>{{ __('realty.fuel_type') }}</span>
                        <span>
                                @foreach($ausstattung['befeuerung']['@attributes'] as $key => $heizung)
                                @if($heizung == 1)
                                    {{  ucfirst( strtolower( $key )) }}
                                @endif
                            @endforeach
                            </span>
                    </li>
                @endif

            </ul>

        </div>
    @endif
</div>
