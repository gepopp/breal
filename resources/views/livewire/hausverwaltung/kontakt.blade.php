<div>

    <section @class([
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
    ])>
        <div class="max-w-md">
            <x-headings>
                <x-slot name="tag">together</x-slot>
                Ihr Kontakt zu Bontus Eybel Immobilien
            </x-headings>
            <p data-aos="fade" data-aos-delay="600">Am liebsten sprechen wir persönlich mit Ihnen über Ihre Anliegen. Wir freuen uns, von Ihnen zu hören und gemeinsam Großartiges zu schaffen.</p>
        </div>
    </section>

    <livewire:contact-form/>
</div>
