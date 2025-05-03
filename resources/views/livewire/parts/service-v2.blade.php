<x-section class="bg-logo-500/3 dark:bg-logo-500/3">
    <div x-data="parallax" class="py-24 lg:px-0 lg:max-w-4xl xl:max-w-6xl mx-auto">
        <div @class(['md:max-w-sm lg:max-w-lg' => !is_array($preparedText), 'max-w-full'])>
            <x-headings>
                <x-slot name="tag">{{ $settings->service_heading }}</x-slot>
                {!! $settings->service_subheading !!}
            </x-headings>
            <div data-aos="fade"
                 class="my-8 md:max-w-1/2">{!! is_array($preparedText) ? $preparedText['firstHalf'] : html_entity_decode($preparedText) !!}</div>

            <div data-aos="fade" class="relative ml-[calc(-50vw+50%)] mr-0 w-[calc(50vw+50%)]">
                <img x-ref="image" src="{{ asset('team-parallax.jpg') }}" alt=""
                     class="object-cover aspect-[4/2] w-full h-[50vh]">
            </div>

            <div data-aos="fade" data-aos-delay="600" class="max-w-full">

                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-24">
{{--                    <div class="relative  min-h-[400px]">--}}
{{--                        <div x-data="serviceFilter"--}}
{{--                             class="absolute inset-0 bg-white -mt-12 p-6 flex flex-col">--}}
{{--                            <flux:input x-model="searchterm" class="border-logo" icon:trailing="funnel" placeholder="Filtern Sie aus {{ count($services) }} Services"/>--}}

{{--                            <div class="border-t border-logo mt-4 pt-4 flex-grow relative">--}}
{{--                                <ul class="absolute inset-0 overflow-y-auto scrollbar scrollbar-thin  !list-none">--}}
{{--                                    <template x-for="service in services" :key="service.id">--}}
{{--                                        <li class="border-b border-logo p-2 !ml-0">--}}
{{--                                            <a :href="service.link" wire:navigate>--}}
{{--                                                <p class="!font-bold !mb-0 !dark:text-logo !text-logo" x-text="service.name"></p>--}}
{{--                                            </a>--}}
{{--                                            <div class="flex space-x-2">--}}
{{--                                                <template x-for="point in service.points">--}}
{{--                                                    <div class="flex space-x-px items-center !text-logo !dark:text-logo/50">--}}
{{--                                                        <svg class="size-3" data-slot="icon" fill="none"--}}
{{--                                                             stroke-width="2" stroke="currentColor"--}}
{{--                                                             viewBox="0 0 24 24"--}}
{{--                                                             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">--}}
{{--                                                            <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                                                  d="M12 4.5v15m7.5-7.5h-15"></path>--}}
{{--                                                        </svg>--}}
{{--                                                        <span class="text-xs"--}}
{{--                                                              x-text="point"></span>--}}
{{--                                                    </div>--}}
{{--                                                </template>--}}
{{--                                            </div>--}}
{{--                                        </li>--}}
{{--                                    </template>--}}
{{--                                </ul>--}}
{{--                            </div>--}}

{{--                            <div class="mt-4 pt-4 border-t border-logo">--}}
{{--                                <x-button href="https://realonline.bontus-eybel.at">Kundenlogin</x-button>--}}
{{--                            </div>--}}


{{--                        </div>--}}
{{--                    </div>--}}
                    <div>&nbsp;</div>
                    <div class="pt-12">{!! $preparedText['secondHalf'] !!}</div>
                </div>
            </div>
        </div>
    </div>
</x-section>
@script
<script>
    Alpine.data('parallax', () => ({
        init() {
            var images = this.$refs.image;
            new window.SimpleParallax(images, {scale: 2, delay: 1, orientation: 'right'});
        }
    }))


    Alpine.data('serviceFilter', () => ({
        originalServices: @entangle('services'),
        services: null,
        searchterm: '',
        fuse: null,
        init() {
            this.services = this.originalServices;

            this.fuse = new window.Fuse(this.originalServices,
                {
                    keys: ['name'],
                    threshold: 0.4
                });

            this.$watch('searchterm', (value) => {
                this.filterServices();
            })
        },
        filterServices() {
            if (this.searchterm.length < 3) {
                this.services = this.originalServices
            } else {
                var items = this.fuse.search(this.searchterm);
                this.services = items.map(resultat => resultat.item);
            }
        }
    }))
</script>
@endscript