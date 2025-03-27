<section>

    <div class="flex flex-col">
        <div class="w-full">
            <h3 data-aos="fade" @class([
        "text-2xl font-bold mb-8",
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
        ])>Sagen Sie uns Hallo!</h3>

            <ul @class([
                 "text-lg font-light space-y-4",
        "text-logo-900" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-900" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-900" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
                ])>
                <li data-aos="fade" data-aos-delay="600">
                    <a href="tel:+43 1 535 36 19" class="underline underline-offset-4" onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'tel': '+43 1 535 36 19' });">
                        +43 1 535 36 19
                    </a>
                </li>
                <li data-aos="fade" data-aos-delay="900">
                    <a href="mailto:office@bontus-eybel.at" class="underline underline-offset-4" onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'email': 'office@bontus-eybel.at' });">
                        office@bontus-eybel.at
                    </a>
                </li>
                <li data-aos="fade" data-aos-delay="1200">
                    Franz Josefs Kai 65, 1010 Wien
                </li>
            </ul>

        </div>
    </div>

</section>
