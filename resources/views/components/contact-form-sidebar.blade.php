<div class="w-full sm:w-1/3">
    <h3 data-aos="fade" data-aos-once="true" @class([
        "text-2xl font-bold mb-8 dark:text-white",
        "text-logo-950" => !Route::is('technik.*', 'makler.*'),
        "text-technik-950" => Route::is('technik.*'),
        "text-makler-950" => Route::is('makler.*'),
        ])>{{ $pagesSettings->contactform_heading }}</h3>

    <ul @class([
                 "text-lg font-light space-y-4 dark:text-white",
        "text-logo-900" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-900" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-900" => \Illuminate\Support\Facades\Route::is('makler.*'),
                ])>
        @if(request()->routeIs('makler.*'))
            <li data-aos="fade" data-aos-delay="600" data-aos-once="true">
                <a href="tel:{{ $pagesSettings->makler_contactform_phone }}" class="underline underline-offset-4"
                   onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'tel': '{{ $pagesSettings->makler_contactform_phone }}' });">
                    {{ $pagesSettings->makler_contactform_phone }}
                </a>
            </li>
            <li data-aos="fade" data-aos-delay="900" data-aos-once="true">
                <a href="mailto:{{ $pagesSettings->contactform_email }}" class="underline underline-offset-4"
                   onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'email': 'o{{ $pagesSettings->makler_contactform_email }}' });">
                    {{ $pagesSettings->makler_contactform_email }}
                </a>
            </li>
            <li data-aos="fade" data-aos-delay="1200" data-aos-once="true">
                {{ $pagesSettings->makler_contactform_address }}
            </li>

        @elseif(request()->routeIs('technik.*'))
            <li data-aos="fade" data-aos-delay="600" data-aos-once="true">
                <a href="tel:{{ $pagesSettings->technik_contactform_phone }}" class="underline underline-offset-4"
                   onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'tel': '{{ $pagesSettings->technik_contactform_phone }}' });">
                    {{ $pagesSettings->technik_contactform_phone }}
                </a>
            </li>
            <li data-aos="fade" data-aos-delay="900" data-aos-once="true">
                <a href="mailto:{{ $pagesSettings->technik_contactform_email }}" class="underline underline-offset-4"
                   onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'email': 'o{{ $pagesSettings->technik_contactform_email }}' });">
                    {{ $pagesSettings->technik_contactform_email }}
                </a>
            </li>
            <li data-aos="fade" data-aos-delay="1200" data-aos-once="true">
                {{ $pagesSettings->technik_contactform_address }}
            </li>
        @else
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
        @endif
    </ul>
</div>