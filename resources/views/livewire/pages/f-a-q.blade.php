<x-section>
    <section @class([
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('makler.*'),
    ])>
        <div class="max-w-md w-full">
            <x-headings>
                <x-slot name="tag">{{ $pagesSettings->faq_header }}</x-slot>
                {{ $pagesSettings->faq_subheader }}
            </x-headings>
        </div>
        <div data-aos="fade" data-aos-delay="600" class="prose max-w-full">

            @if(!is_array($preparedText))
                {!! html_entity_decode( $preparedText ) !!}
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-12 w-full">
                    <div>{!! $preparedText['firstHalf'] !!}</div>
                    <div>{!! $preparedText['secondHalf'] !!}</div>
                </div>
            @endif
        </div>
    </section>


    <section x-data="{ current: 0 }" class="mt-12">
        @foreach($faqs as $index => $faq)
            <div class="py-4 border-b border-logo">
                <p class="!text-lg !font-bold flex justify-between items-center cursor-pointer !text-logo !dark:text-white" x-on:click="current = {{ $index }}">
                    <span class="dark:text-white">{{ blank($faq['question'][app()->getLocale() ?? 'de']) ? $faq['question']['de'] : $faq['question'][app()->getLocale() ?? 'de'] }}</span>
                    <span>
                        <template x-if="current != {{ $index }}">
                             <svg class="size-8" data-slot="icon" fill="none" stroke-width="4" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                             </svg>
                        </template>
                        <template x-if="current == {{ $index }}">
                             <svg class="size-8" data-slot="icon" fill="none" stroke-width="4" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"></path>
                             </svg>
                        </template>

                    </span>
                </p>
                <div x-show="current == {{ $index }}" x-collapse>
                    <div class="prose">
                        {!! blank($faq['answer'][app()->getLocale() ?? 'de']) ? $faq['answer']['de'] : $faq['answer'][app()->getLocale() ?? 'de'] !!}
                    </div>
                    <div class="h-px w-full bg-logo mt-8"></div>
                    <div class="prose bg-logo p-4 text-white flex flex-col items-center justify-center">
                        <h5 class="!text-lg !font-bold !text-white">Sie haben Fragen oder Anregungen zu diesem Thema?</h5>
                        <div class="max-w-xs mt-4">
                            <x-button href="{{ $faq['link'] }}" :ondark="true">kontaktieren sie uns</x-button>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </section>
</x-section>
