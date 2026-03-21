<div>

    {{-- ============================================ --}}
    {{-- HERO SECTION --}}
    {{-- ============================================ --}}
    <div class="bg-logo-500">
        <div class="section !py-0 text-white flex">
            <div class="hidden md:block w-1/5 mb-12 rounded-bl-2xl overflow-hidden">
                <img src="{{ asset('hausverwalterservice-header-links.jpg') }}" class="w-full min-h-full object-cover object-center" alt="Verwalterservice"/>
            </div>

            <div class="w-full md:w-3/5 shadow-2xl text-white flex flex-col items-center px-6 md:px-12 py-24 relative">

                <div class="mx-auto">
                    <img src="{{ asset('be_Logo_RGB_white.svg') }}" class="h-12" alt="BE Real Immobilien">
                </div>

                <div class="text-logo-50 text-center font-medium flex items-center gap-2 mt-4">
                    {!! str_replace('•', '<div class="size-1 bg-logo-50 rounded"></div>', $settings->hero_tagline) !!}
                </div>
                <h1 class="text-3xl md:text-5xl text-center mt-4 font-logo">
                    {{ $settings->hero_headline }}
                </h1>

                <div class="flex flex-wrap w-full gap-2 items-center justify-center mt-4">
                    <p class="flex items-center gap-2">
                        <svg class="size-5" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"></path>
                        </svg>
                        <a href="mailto:{{ $settings->hero_email }}" class="text-sm">{{ $settings->hero_email }}</a>
                    </p>
                    <div class="size-1 bg-logo-50 rounded"></div>
                    <p class="flex items-center gap-2">
                        <svg class="size-5" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"></path>
                        </svg>
                        <a href="tel:{{ str_replace(' ', '', $settings->hero_phone) }}" class="text-sm">{{ $settings->hero_phone }}</a>
                    </p>
                </div>

                <div class="absolute top-full left-0 w-full bg-logo-500 rounded-b-full py-4 px-4 md:px-12 shadow-2xl z-10">
                    <div class="border-t border-white flex justify-between p-4">
                        <div class="flex gap-2 items-center text-sm">
                            <svg class="size-8 shrink-0" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z"></path>
                            </svg>
                            <p class="!text-sm !font-light">{!! str_replace(' ', '<br> ', $settings->hero_feature_1) !!}</p>
                        </div>
                        <div class="flex gap-2 items-center text-sm">
                            <svg class="size-8 shrink-0" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                            </svg>
                            <p class="!text-sm !font-light">{!! str_replace(' ', '<br> ', $settings->hero_feature_2) !!}</p>
                        </div>
                        <div class="flex gap-2 items-center text-sm">
                            <svg class="size-8 shrink-0" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42"></path>
                            </svg>
                            <p class="!text-sm !font-light">{!! str_replace(' ', '<br> ', $settings->hero_feature_3) !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block w-1/5 mb-12 rounded-br-2xl overflow-hidden">
                <img src="{{ asset('hausverwalterservice-header-rechts.jpg') }}" class="w-full min-h-full object-cover object-center" alt="Verwalterservice"/>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- PROFESSIONAL SERVICES SECTION --}}
    {{-- ============================================ --}}
    <x-section class="mt-16 !py-16 md:!py-32">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24 items-center">
            <div data-aos="fade-up">
                <x-headings :level="2">
                    <x-slot name="tag">{{ $settings->section_1_tag }}</x-slot>
                    {{ $settings->section_1_headline }}
                </x-headings>

                <p class="text-base xl:text-lg font-light mt-4" data-aos="fade" data-aos-delay="300">
                    {{ $settings->section_1_text }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-8" data-aos="fade" data-aos-delay="500">
                    <div class="flex items-center gap-2">
                        <svg class="size-5 text-logo-300 shrink-0" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                        <span class="text-sm">{{ $settings->section_1_feature_1 }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-5 text-logo-300 shrink-0" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                        <span class="text-sm">{{ $settings->section_1_feature_2 }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-5 text-logo-300 shrink-0" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                        <span class="text-sm">{{ $settings->section_1_feature_3 }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-5 text-logo-300 shrink-0" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                        <span class="text-sm">{{ $settings->section_1_feature_4 }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-5 text-logo-300 shrink-0" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                        <span class="text-sm">{{ $settings->section_1_feature_5 }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="size-5 text-logo-300 shrink-0" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                        <span class="text-sm">{{ $settings->section_1_feature_6 }}</span>
                    </div>
                </div>

                <div class="mt-8" data-aos="fade" data-aos-delay="700">
                    <x-button href="mailto:{{ $settings->hero_email }}">{{ $settings->section_1_cta }}</x-button>
                </div>
            </div>

            <div class="relative" data-aos="fade-up" data-aos-delay="200">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ asset('verwalterservice1.jpg') }}" alt="Professioneller Verwalterservice" class="w-full h-full object-cover"/>
                </div>
                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-logo-300/20 rounded-2xl -z-10"></div>
                <div class="absolute -top-4 -right-4 w-32 h-32 bg-logo-500/10 rounded-full -z-10"></div>
            </div>
        </div>
    </x-section>

    {{-- ============================================ --}}
    {{-- OUR SERVICES GRID --}}
    {{-- ============================================ --}}
    <x-section class="bg-logo-500/5 dark:bg-logo-500/10 !py-16 md:!py-32">
        <div class=" max-w-2xl mx-auto mb-16 flex flex-col items-center">
            <x-headings :level="2">
                <x-slot name="tag">{{ $settings->section_2_tag }}</x-slot>
                {{ $settings->section_2_headline }}
            </x-headings>
            <p class="text-center text-base xl:text-lg font-light mt-4" data-aos="fade" data-aos-delay="300">
                {{ $settings->section_2_text }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach($serviceCards as $index => $card)
            <div class="bg-white dark:bg-logo-950 rounded-xl p-8 shadow-sm hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="size-14 rounded-xl bg-logo-500/10 dark:bg-logo-300/10 flex items-center justify-center mb-5">
                    @svg('heroicon-o-' . $card->icon, 'size-7 text-logo-500 dark:text-logo-300')
                </div>
                <h3 class="text-lg font-bold mb-2">{{ $card->name }}</h3>
                <p class="text-sm font-light">{{ $card->text }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="600">
            <p class="text-sm font-light mb-4">{{ $settings->section_2_cta_text }}</p>
            <x-button href="mailto:{{ $settings->hero_email }}">{{ $settings->section_2_cta_button }}</x-button>
        </div>
    </x-section>

    {{-- ============================================ --}}
    {{-- GUARANTEE / TRUST SECTION --}}
    {{-- ============================================ --}}
    <x-section class="bg-logo !py-16 md:!py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-start">
            <div data-aos="fade-up">
                <x-headings :level="2" :ondark="true">
                    <x-slot name="tag">{{ $settings->section_3_tag }}</x-slot>
                    {{ $settings->section_3_headline }}
                </x-headings>
                <p class="text-base xl:text-lg font-light text-white/80 mt-4" data-aos="fade" data-aos-delay="300">
                    {{ $settings->section_3_text }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-white">
                @foreach($featureCards as $index => $card)
                <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="flex items-center gap-3 mb-2">
                        @svg('heroicon-o-' . $card->icon, 'size-6 text-logo-300 shrink-0')
                        <h4 class="font-bold">{{ $card->name }}</h4>
                    </div>
                    <p class="text-sm font-light text-white/70">{{ $card->text }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </x-section>

    {{-- ============================================ --}}
    {{-- HOW IT WORKS SECTION --}}
    {{-- ============================================ --}}
    <x-section class="!py-16 md:!py-32">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24 items-center">
            <div class="relative order-last md:order-first" data-aos="fade-up">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ asset('verwalterservice2.jpg') }}" alt="So funktioniert der Verwalterservice" class="w-full h-full object-cover"/>
                </div>
                <div class="absolute -bottom-6 -right-6 bg-logo-500 text-white rounded-2xl p-6 shadow-lg hidden md:block" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center gap-3">
                        <svg class="size-8 text-logo-300" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-bold">{{ $settings->section_4_stat_number }}</p>
                            <p class="text-xs text-white/70">{{ $settings->section_4_stat_text }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="200">
                <x-headings :level="2">
                    <x-slot name="tag">{{ $settings->section_4_tag }}</x-slot>
                    {{ $settings->section_4_headline }}
                </x-headings>

                <div class="mt-8 space-y-8">
                    <div class="flex gap-5">
                        <div class="shrink-0">
                            <div class="size-12 rounded-full bg-logo-500 dark:bg-logo-300 text-white dark:text-logo-950 flex items-center justify-center font-logo text-xl font-bold">1</div>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">{{ $settings->step_1_title }}</h4>
                            <p class="text-sm font-light mt-1">{{ $settings->step_1_text }}</p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="shrink-0">
                            <div class="size-12 rounded-full bg-logo-500 dark:bg-logo-300 text-white dark:text-logo-950 flex items-center justify-center font-logo text-xl font-bold">2</div>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">{{ $settings->step_2_title }}</h4>
                            <p class="text-sm font-light mt-1">{{ $settings->step_2_text }}</p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="shrink-0">
                            <div class="size-12 rounded-full bg-logo-500 dark:bg-logo-300 text-white dark:text-logo-950 flex items-center justify-center font-logo text-xl font-bold">3</div>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">{{ $settings->step_3_title }}</h4>
                            <p class="text-sm font-light mt-1">{{ $settings->step_3_text }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-section>

    {{-- ============================================ --}}
    {{-- CTA / CONTACT SECTION --}}
    {{-- ============================================ --}}
    <section class="py-16 md:py-32 bg-logo-500/5 dark:bg-logo-500/10">
        <div class="lg:max-w-4xl xl:max-w-6xl mx-auto px-4">
            <div data-aos="fade-up" class="flex flex-col items-center">
                <x-headings :level="2">
                    <x-slot name="tag">{{ $settings->cta_tag }}</x-slot>
                    {{ $settings->cta_headline }}
                </x-headings>
                <p class="text-base xl:text-lg font-light mt-4 max-w-2xl mx-auto" data-aos="fade" data-aos-delay="300">
                    {{ $settings->cta_text }}
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8" data-aos="fade-up" data-aos-delay="500">
                <x-button href="mailto:{{ $settings->hero_email }}">{{ $settings->cta_button }}</x-button>
                <a href="tel:{{ str_replace(' ', '', $settings->hero_phone) }}" class="flex items-center gap-2 text-logo-500 dark:text-logo-300 font-medium hover:underline">
                    <svg class="size-5" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"></path>
                    </svg>
                    {{ $settings->hero_phone }}
                </a>
            </div>
        </div>
    </section>

</div>
