<x-section class="bg-logo">
    <div @class(["mx-auto grid grid-cols-1 md:grid-cols-2 md:gap-12 lg:gap-24 relative w-full"])>
        <div @class(['flex justify-center items-center md:min-h-[70vh] mt-12 md:mt-0'])>
            <div @class(['md:max-w-sm lg:max-w-lg'])>
                <x-headings :ondark="true">
                    <x-slot name="tag">{{ $settings->about_header }}</x-slot>
                    {!! $settings->about_subheader !!}
                </x-headings>
                <div data-aos="fade" data-aos-delay="600" class="prose !text-white">
                    {!! html_entity_decode( $settings->about_text ) !!}
                </div>
            </div>
        </div>
        <div @class(['order-first md:order-last -mx-4 md:mx-0 md:ml-0 relative overflow-hidden flex items-center p-4 md:p-0 relative'])>
            @if(!is_null($media))
                <img src="{{ $media?->getUrl() }}" srcset="{{ $media?->getSrcset() }}" alt="{{ $settings->about_image_alt }}" data-aos="fade"
                     data-aos-delay="600"
                     class="aspect-video object-cover rounded-xl shadow-lg shadow-white/10"/>

                @if(!empty($settings->about_video_embed_code))

                    <div class="absolute inset-0 flex justify-center items-center text-logo">
                        <flux:modal.trigger name="video">
                            <svg class="size-24" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z"></path>
                            </svg>
                        </flux:modal.trigger>
                    </div>

                    <flux:modal name="video" class="!p-0 !border-logo-950 !w-[1080px] !max-w-full !overflow-hidden !bg-logo-950">
                        <div class="aspect-video w-[1080px] max-w-full object-cover overflow-hidden z-[9999]">
                            {!! $settings->about_video_embed_code !!}
                        </div>
                    </flux:modal>

                @endif

            @endif
        </div>
    </div>
    <div class="flex justify-center mt-8">
        <x-button href="{{ route('hausverwaltung.kontakt') }}" :ondark="true">kontakt</x-button>
    </div>
</x-section>
