<div class="pt-4 mt-4 border-t border-gray-200">
    <h3>{{ __('realty.price_information') }}</h3>
    @if($realty->vermarktungsart == 'miete')
        <ul>
            @if(array_key_exists('gesamtmietebrutto', $prices))
                <li class="flex justify-between !ml-0">
                    <span>{{ __('realty.total_rent') }}</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['gesamtmietebrutto'], 'EUR', 'de') }}</span>
                </li>
            @endif


            @if(array_key_exists('gesamtmietenetto', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>{{ __('realty.net_rent') }}</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['gesamtmietenetto'], 'EUR', 'de') }}</span>
                </li>
            @endif
            @if(array_key_exists('betriebskostennetto', $prices))
                <li class="flex justify-between !ml-0">
                    <span>{{ __('realty.operating_costs_net') }}</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['betriebskostennetto'], 'EUR', 'de') }}</span>
                </li>
            @endif

            @if(array_key_exists('kaution_text', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>{{ __('realty.deposit') }}</span>
                    <span>{{ $prices['kaution_text'] }}</span>
                </li>
            @endif

            @if(array_key_exists('aussen_courtage', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>{{ $prices['aussen_courtage'] }}</span>
                </li>
            @endif

        </ul>
    @endif


    @if($realty->vermarktungsart == 'kauf')
        <ul>
            @if(array_key_exists('kaufpreisbrutto', $prices))
                <li class="flex justify-between !ml-0">
                    <span>{{ __('realty.purchase_price') }}</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['kaufpreisbrutto'], 'EUR', 'de') }}</span>
                </li>
            @endif


            @if(array_key_exists('kaufpreisnetto', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>{{ __('realty.net_purchase_price') }}</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['kaufpreisnetto'], 'EUR', 'de') }}</span>
                </li>
            @endif
            @if(array_key_exists('betriebskostennetto', $prices))
                <li class="flex justify-between !ml-0">
                    <span>{{ __('realty.operating_costs_net') }}</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['betriebskostennetto'], 'EUR', 'de') }}</span>
                </li>
            @endif

            @if(array_key_exists('aussen_courtage', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>{{ $prices['aussen_courtage'] }}</span>
                </li>
            @endif

        </ul>
    @endif
</div>
