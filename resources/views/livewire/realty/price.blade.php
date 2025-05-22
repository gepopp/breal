<div class="pt-4 mt-4 border-t border-gray-200">
    <h3>Preisinformation</h3>
    @if($realty->vermarktungsart == 'miete')
        <ul>
            @if(array_key_exists('gesamtmietebrutto', $prices))
                <li class="flex justify-between !ml-0">
                    <span>Gesamtmiete</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['gesamtmietebrutto'], 'EUR', 'de') }}</span>
                </li>
            @endif


            @if(array_key_exists('gesamtmietenetto', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>Nettomiete</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['gesamtmietenetto'], 'EUR', 'de') }}</span>
                </li>
            @endif
            @if(array_key_exists('betriebskostennetto', $prices))
                <li class="flex justify-between !ml-0">
                    <span>Betreibskosten-Netto</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['betriebskostennetto'], 'EUR', 'de') }}</span>
                </li>
            @endif

            @if(array_key_exists('kaution_text', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>Kaution</span>
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
                    <span>Kaufpreis</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['kaufpreisbrutto'], 'EUR', 'de') }}</span>
                </li>
            @endif


            @if(array_key_exists('kaufpreisnetto', $prices))
                <li class="flex justify-between !ml-0 mt-8">
                    <span>Netto-Kaufpreis</span>
                    <span>{{ \Illuminate\Support\Number::currency($prices['kaufpreisnetto'], 'EUR', 'de') }}</span>
                </li>
            @endif
            @if(array_key_exists('betriebskostennetto', $prices))
                <li class="flex justify-between !ml-0">
                    <span>Betreibskosten-Netto</span>
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
