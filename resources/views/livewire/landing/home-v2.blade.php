<div class="home-v2">
    {{-- Hero Section - Same as original --}}
    <livewire:parts.landing-hero/>

    {{-- Intro Layout - Same as original --}}
    @if($settings->intro_layout == 'text_image')
        <livewire:parts.image-text/>
    @endif

    @if($settings->intro_layout == 'two_columns')
        <livewire:parts.two-column-text/>
    @endif

    {{-- About Section - Enhanced Styling --}}
    <div class="relative">
        <div class="absolute inset-0 bg-logo opacity-5 dark:opacity-10"></div>
        <livewire:parts.about/>
    </div>

    {{-- Timeline - Same as original --}}
    <livewire:parts.timeline/>

    {{-- Three Business Sections Emphasis --}}
    <section class="py-24 bg-white dark:bg-logo-950 relative overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-64 h-64 bg-logo-500/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-logo-500/5 rounded-full translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-20" data-aos="fade-up">
                <div class="inline-block mb-6">
                    <span class="bg-logo text-white px-6 py-2 rounded-full text-sm font-bold uppercase tracking-wider">
                        Unsere Bereiche
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 dark:text-white">
                    Drei Säulen, eine Kompetenz
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Von der Verwaltung über die Vermittlung bis zur Technik – wir bieten alles aus einer Hand
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Hausverwaltung Card --}}
                <div class="group bg-white dark:bg-logo-900 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-logo-200 dark:border-logo-700 hover:border-logo-500 dark:hover:border-logo-500" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-8 lg:p-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-16 h-16 bg-logo-100 dark:bg-logo-800 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-logo-600 dark:text-logo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="text-6xl font-logo text-logo-500/10 dark:text-logo-500/20">01</span>
                        </div>

                        <h3 class="text-2xl font-bold mb-4 text-logo-600 dark:text-logo-400 font-logo">
                            Hausverwaltung
                        </h3>

                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Professionelles Management für Zinshäuser, Wohnungseigentum und Gewerbeimmobilien mit persönlicher Betreuung.
                        </p>

                        <a href="{{ route('hausverwaltung.home') }}" class="inline-flex items-center gap-2 text-logo-600 dark:text-logo-400 font-semibold group-hover:gap-4 transition-all">
                            Mehr erfahren
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="h-2 bg-logo-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                </div>

                {{-- Makler Card --}}
                <div class="group bg-white dark:bg-logo-900 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-makler-200 dark:border-makler-700 hover:border-makler-500 dark:hover:border-makler-500" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-8 lg:p-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-16 h-16 bg-makler-100 dark:bg-makler-800 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-makler-600 dark:text-makler-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <span class="text-6xl font-logo text-makler-500/10 dark:text-makler-500/20">02</span>
                        </div>

                        <h3 class="text-2xl font-bold mb-4 text-makler-600 dark:text-makler-400 font-logo">
                            Makler
                        </h3>

                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Immobilienvermittlung im Bereich Wohnen und Gewerbe mit maßgeschneiderter Beratung und umfassender Expertise.
                        </p>

                        <a href="{{ route('makler.home') }}" class="inline-flex items-center gap-2 text-makler-600 dark:text-makler-400 font-semibold group-hover:gap-4 transition-all">
                            Mehr erfahren
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="h-2 bg-makler-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                </div>

                {{-- Technik Card --}}
                <div class="group bg-white dark:bg-logo-900 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-technik-200 dark:border-technik-700 hover:border-technik-500 dark:hover:border-technik-500" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-8 lg:p-10">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-16 h-16 bg-technik-100 dark:bg-technik-800 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-technik-600 dark:text-technik-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-6xl font-logo text-technik-500/10 dark:text-technik-500/20">03</span>
                        </div>

                        <h3 class="text-2xl font-bold mb-4 text-technik-600 dark:text-technik-400 font-logo">
                            Technik
                        </h3>

                        <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Technische Betreuung und moderne Lösungen für Ihre Immobilien mit Expertise und Weitblick.
                        </p>

                        <a href="{{ route('technik.home') }}" class="inline-flex items-center gap-2 text-technik-600 dark:text-technik-400 font-semibold group-hover:gap-4 transition-all">
                            Mehr erfahren
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="h-2 bg-technik-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Service Section - Same as original --}}
    <livewire:parts.service-v2/>

    {{-- Competences - Same as original --}}
    <livewire:parts.competences/>

    {{-- References - Same as original --}}
    <livewire:parts.references
            company="$company"
            header="{{ $settings->reference_header }}"
            subheader="{{ $settings->reference_subheader }}"
            text="{{ $settings->reference_introtext }}"
    />

    {{-- LinkedIn Wall - Same as original --}}
    <livewire:parts.linke-in-wall/>

    {{-- Contact - Same as original --}}
    <livewire:parts.contact/>
</div>

@assets
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
</script>
@endassets
