<div class="pt-4 mt-4 border-t border-gray-200">
    <h3>{{ __('realty.equipment') }}</h3>
    <ul>
        @if(array_key_exists('boden', $ausstattung))
            <li class="!ml-0 flex justify-between">
                @php
                    $boden = '';
                        foreach($ausstattung['boden']['@attributes'] as $key => $heizung){
                                              if($heizung == 1){
                                                  $boden .=  ucfirst( strtolower( $key )) . ', ';
                                              }
                                              }
                        $boden = rtrim($boden, ', ');
                @endphp
                <span>{{ __('realty.floor') }}</span>
                <span>{{ $boden }}</span>
            </li>
        @endif
            @if(array_key_exists('bad', $ausstattung))
                <li class="!ml-0 flex justify-between">
                    @php
                        $boden = '';
                            foreach($ausstattung['bad']['@attributes'] as $key => $heizung){
                                                  if($heizung == 1){
                                                      $boden .=  ucfirst( strtolower( $key )) . ', ';
                                                  }
                                                  }
                            $boden = rtrim($boden, ', ');
                    @endphp
                    <span>{{ __('realty.bathroom') }}</span>
                    <span>{{ $boden }}</span>
                </li>
            @endif
            @if(array_key_exists('kueche', $ausstattung))
                <li class="!ml-0 flex justify-between">
                    @php
                        $boden = '';
                            foreach($ausstattung['kueche']['@attributes'] as $key => $heizung){
                                                  if($heizung == 1){
                                                      $boden .=  ucfirst( strtolower( $key )) . ', ';
                                                  }
                                                  }
                            $boden = rtrim($boden, ', ');
                    @endphp
                    <span>{{ __('realty.kitchen') }}</span>
                    <span>{{ $boden }}</span>
                </li>
            @endif
            @if(array_key_exists('klimatisiert', $ausstattung) && $ausstattung['klimatisiert'] == 1)
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.air_conditioned') }}</span>
                    <span>{{ __('common.yes') }}</span>
                </li>
            @endif
            @if(array_key_exists('fahrstuhl', $ausstattung))
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.elevator') }}</span>
                    <span>{{ __('common.yes') }}</span>
                </li>
            @endif
            @if(array_key_exists('stellplatzart', $ausstattung))
                <li class="!ml-0 flex justify-between">
                    @php
                        $boden = '';
                            foreach($ausstattung['stellplatzart']['@attributes'] as $key => $heizung){
                                                  if($heizung == 1){
                                                      $boden .=  ucfirst( strtolower( $key )) . ', ';
                                                  }
                                                  }
                            $boden = rtrim($boden, ', ');
                    @endphp
                    <span>{{ __('realty.parking_space') }}</span>
                    <span>{{ $boden }}</span>
                </li>
            @endif
            @if(array_key_exists('gartennutzung', $ausstattung))
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.garden_use') }}</span>
                    <span>{{ __('common.yes') }}</span>
                </li>
            @endif
            @if(array_key_exists('wasch_trockenraum', $ausstattung) && $ausstattung['wasch_trockenraum'])
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.laundry_room') }}</span>
                    <span>{{ __('common.yes') }}</span>
                </li>
            @endif
            @if(array_key_exists('wintergarten', $ausstattung) && $ausstattung['wintergarten'])
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.laundry_room') }}</span>
                    <span>{{ __('common.yes') }}</span>
                </li>
            @endif
            @if(array_key_exists('abstellraum', $ausstattung) && $ausstattung['abstellraum'])
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.storage_room') }}</span>
                    <span>{{ __('common.yes') }}</span>
                </li>
            @endif
            @if(array_key_exists('fahrradraum', $ausstattung) && $ausstattung['fahrradraum'])
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.storage_room') }}</span>
                    <span>{{ __('common.yes') }}</span>
                </li>
            @endif
            @if(array_key_exists('unterkellert', $ausstattung))
                <li class="!ml-0 flex justify-between">
                    <span>{{ __('realty.basement') }}</span>
                    <span>{{ $ausstattung['unterkellert']['keller'] }}</span>
                </li>
            @endif
    </ul>
</div>
