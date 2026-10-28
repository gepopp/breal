@php
    $section = \App\Services\CompanyInfo::section();
    $phone = \App\Services\CompanyInfo::phone();
@endphp
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
        <li data-aos="fade" data-aos-delay="600" data-aos-once="true">
            <a href="{{ \App\Services\CompanyInfo::phoneUri() }}" class="underline underline-offset-4"
               onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'tel': '{{ $phone }}' });">
                {{ $phone }}
            </a>
        </li>
        <li data-aos="fade" data-aos-delay="900" data-aos-once="true">
            <a href="{{ \App\Services\CompanyInfo::mailtoUri($section['email']) }}" class="underline underline-offset-4"
               onclick="gtag('event', 'email_click', {'event_category': 'engagement', 'event_label': 'Contact', 'email': '{{ $section['email'] }}' });">
                {{ $section['email'] }}
            </a>
        </li>
        <li data-aos="fade" data-aos-delay="1200" data-aos-once="true">
            {{ \App\Services\CompanyInfo::address() }}
        </li>
    </ul>
</div>
