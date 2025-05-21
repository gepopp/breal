<x-section>
    <div class="flex space-x-8">
        <div class="w-2/3">
            <h5 class="text-opacity-50 mb-4">
                {{ $realty->strasse }} {{ $realty->hausnummer }}, {{ $realty->plz }} {{ $realty->ort }}
                | {{ ucfirst(strtolower( $realty-> type)) }}
                | Objektnummer: {{ $realty->objektnr_extern }}
            </h5>
            <h1 class="text-2xl font-bold">{{ $realty->title }}</h1>
            <livewire:subparts.realty-slider :realty="$realty"/>

            <div class="prose">
                {!! $realty->beschreibung !!}
            </div>
        </div>
        <div class="w-1/3">
            <div class="prose">
                @php
                    $contact = $realty->data['kontaktperson'];
                @endphp
                <img src="{{ $contact['foto']['daten']['pfad'] }}" alt="{{ $contact['vorname'] }} {{ $contact['name'] }}" class="aspect-square object-cover object-top">

                <h3>{{ $contact['anrede'] }} {{ $contact['vorname'] }} {{ $contact['name'] }}</h3>
                <p class="flex items-center space-x-2">
                    <svg class="size-5" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"></path>
                    </svg>
                    <span>
                        <a href="mailto:{{ $contact['email_direkt'] }}">{{ $contact['email_direkt'] }}</a>
                    </span>
                </p>
                @if(array_key_exists('tel_zentrale', $contact) || array_key_exists('tel_handy', $contact))
                    <p class="flex items-center space-x-2">
                        <svg class="size-5" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"></path>
                        </svg>
                        <span>
                        <a href="tel:{{ $contact['tel_zentrale'] ?? $contact['tel_handy'] ?? '' }}">{{ $contact['tel_zentrale'] ?? $contact['tel_handy'] ?? '' }}</a>
                    </span>
                    </p>
                @endif

                <div class="pt-4 mt-4 border-t border-gray-200">
                    @php
                        $settings = app(\App\Settings\PagesSettings::class);
                    @endphp
                    <h3>bereal makler</h3>
                    <p class="flex items-center space-x-2">
                        <svg class="size-5" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"></path>
                        </svg>
                        <span>
                        <a href="mailto:{{ $settings->makler_contactform_email }}">{{ $settings->makler_contactform_email }}</a>
                    </span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <svg class="size-5" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"></path>
                        </svg>
                        <span>
                        <a href="tel:{{ $settings->makler_contactform_phone }}">{{ $settings->makler_contactform_phone }}</a>
                    </span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <svg class="size-5" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"></path>
                        </svg>
                        <span>
                        <span>{!! $settings->makler_contactform_address !!}</span>
                    </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-section>
