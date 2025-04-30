<div>
    @php
        use Illuminate\Support\Facades\Route;
    @endphp
    <div class="flex flex-col sm:flex-row sm:space-x-8">
        @if($sidebar)
            <div class="w-full sm:w-1/3">
                <h3 data-aos="fade" data-aos-once="true" @class([
        "text-2xl font-bold mb-8 dark:text-white",
        "text-logo-950" => !Route::is('technik.*', 'immobilien.*'),
        "text-technik-950" => Route::is('technik.*'),
        "text-makler-950" => Route::is('immobilien.*'),
        ])>{{ $pagesSettings->contactform_heading }}</h3>

                <ul @class([
                 "text-lg font-light space-y-4 dark:text-white",
        "text-logo-900" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-900" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-900" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
                ])>
                    <li data-aos="fade" data-aos-delay="600" data-aos-once="true">
                        <a href="tel:{{ $pagesSettings->contactform_phone }}" class="underline underline-offset-4"
                           onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'tel': '{{ $pagesSettings->contactform_phone }}' });">
                            {{ $pagesSettings->contactform_phone }}
                        </a>
                    </li>
                    <li data-aos="fade" data-aos-delay="900" data-aos-once="true">
                        <a href="mailto:{{ $pagesSettings->contactform_email }}" class="underline underline-offset-4"
                           onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'email': 'o{{ $pagesSettings->contactform_email }}' });">
                            {{ $pagesSettings->contactform_email }}
                        </a>
                    </li>
                    <li data-aos="fade" data-aos-delay="1200" data-aos-once="true">
                        {{ $pagesSettings->contactform_address }}
                    </li>
                </ul>
            </div>
        @endif

        <div x-data="{ is_sent: @entangle('is_sent').live }" @class(["overflow-hidden", "sm:w-2/3" => $sidebar])>
{{--                        <p @click="is_sent = !is_sent">toggle</p>--}}
            <div class="w-[200%] min-h-full grid grid-cols-2 gap-x-4 pl-2 transition-all duration-500 ease-in-out"
                 :class="is_sent ? '-translate-x-1/2' : 'transalte-x-0'">


                <form wire:submit="save"
                      class="space-y-4 mt-24 sm:mt-0 grid grid-cols-1 md:grid-cols-2 gap-y-4 md:gap-x-4">
                    <flux:input wire:model="data.firstname" label="Vorname" required badge="Pflichtfeld"/>
                    <flux:input wire:model="data.lastname" label="Nachname" required badge="Pflichtfeld"/>
                    <flux:input wire:model="data.email" label="E-Mail-Adresse" type="email" required badge="Pflichtfeld"/>
                    <flux:input wire:model="data.phone" label="Telefonnummer" type="tel" required badge="Pflichtfeld"/>
                    <div class="md:col-span-2 space-y-4">
                        @if($address)
                            <flux:input wire:model="data.address" label="Adresse" required badge="Pflichtfeld"/>
                        @endif
                        <flux:editor wire:model="data.message" label="Nachricht" required badge="Pflichtfeld"/>
                        <flux:checkbox wire:model="data.terms"
                                       label="Ich bin mit der Verarbeitung und Speicherung meiner Daten, sowie mit der Kontaktaufnahme via E-Mail oder Telefon im Zuge der Bearbeitung meiner Anfrage einverstanden."/>
                    </div>
                    <x-button>absenden</x-button>
                </form>
                <div class="bg-logo text-white p-4 flex flex-col justify-center items-center">
                    <div class="max-w-xs text-center">
                        <h5 class="text-2xl font-bold">Vielen Dank!</h5>
                        <p class="!text-sm font-thin">
                            Bitte prüfen Sie ihren Posteingang,
                            wir haben Ihnen ein E-Mail zum Bestätigen Ihrer Anmeldung gesendet.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
