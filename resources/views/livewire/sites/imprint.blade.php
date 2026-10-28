<x-section>
    <div>
        <div>
            <div>
                <div>
                    <x-headings level="1">
                        <x-slot name="tag">{{ __('legal.imprint_tag') }}</x-slot>
                        {{ __('legal.imprint_title') }}
                    </x-headings>
                </div>
            </div>
            <div>
                <div class="prose">
                    <p>{{ __('legal.imprint_intro') }}</p>

                    @foreach($companies as $company)
                        <h2>{{ $company['legal_name'] }}</h2>
                        <p>
                            <strong>{{ __('legal.address') }}:</strong> {{ \App\Services\CompanyInfo::address() }}<br>
                            <strong>{{ __('legal.managing_directors') }}:</strong> {{ implode(', ', $company['managing_directors']) }}<br>
                            <strong>{{ __('legal.firmenbuch') }}:</strong> {{ $company['firmenbuch'] }}<br>
                            @foreach(['firmenbuchgericht', 'uid', 'gewerbe', 'kammer', 'aufsichtsbehoerde'] as $field)
                                @if(filled($company[$field] ?? null))
                                    <strong>{{ __('legal.'.$field) }}:</strong> {{ $company[$field] }}<br>
                                @endif
                            @endforeach
                            <strong>{{ __('legal.phone') }}:</strong> <a href="{{ \App\Services\CompanyInfo::phoneUri() }}">{{ \App\Services\CompanyInfo::phone() }}</a><br>
                            <strong>{{ __('legal.fax') }}:</strong> {{ \App\Services\CompanyInfo::fax() }}<br>
                            <strong>{{ __('legal.email') }}:</strong> <a href="{{ \App\Services\CompanyInfo::mailtoUri($company['email']) }}">{{ $company['email'] }}</a>
                        </p>
                    @endforeach

                    <h2>{{ __('legal.media_title') }}</h2>
                    <p>
                        {{ __('legal.media_text', ['name' => \App\Services\CompanyInfo::section(\App\Enums\CompaniesEnum::Hausverwaltung->name)['legal_name'], 'address' => \App\Services\CompanyInfo::address()]) }}
                        @foreach(['owners' => 'legal.media_owners', 'editorial_line' => 'legal.media_line'] as $key => $label)
                            @if(filled(config('company.media.'.$key)))
                                <br><strong>{{ __($label) }}:</strong> {{ config('company.media.'.$key) }}
                            @endif
                        @endforeach
                    </p>

                    <h2>{{ __('legal.dispute_title') }}</h2>
                    <p>
                        {{ __('legal.dispute_text') }}
                        <a href="https://ec.europa.eu/consumers/odr/" rel="noopener" target="_blank">https://ec.europa.eu/consumers/odr/</a>.<br>
                        {{ __('legal.dispute_email') }}
                    </p>

                    <h2>{{ __('legal.consumer_title') }}</h2>
                    <p>{{ __('legal.consumer_text') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-section>
