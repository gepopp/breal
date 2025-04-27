<x-section>
    <section @class([
        "mb-12",
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('immobilien.*'),
    ])>
        <div class="max-w-md w-full">
            <div @class([
        "tagged-heading mb-4",
        "text-logo-500" => !Route::is('technik.*', 'immobilien.*'),
        "text-technik-500" => Route::is('technik.*'),
        "text-makler-500" => Route::is('immobilien.*'),
])>
                <div @class([
        "flex items-center font-logo text-3xl md:text-4xl lg:text-5xl xl:text-7xl font-extrabold rounded tracking-wide lowercase",
        "before:content-['be'] before:h-[32px] before:mb-[6px] before:md:h-[36px] before:lg:h-[42px] before:xl:h-[62px] before:align-baseline before:rounded before:px-2 before:tracking-wide dark:before:bg-white before:bg-logo dark:before:text-logo before:mr-2",
        "text-logo-500" => !Route::is('technik.*', 'immobilien.*'),
        "text-technik-500" => Route::is('technik.*'),
        "text-makler-500" => Route::is('immobilien.*'),
        "before:text-white before:bg-logo"
        ])>
                    <span class="font-logo dark:text-white">{{ $pagesSettings->services_header }}</span>
                </div>
                <div class="text-xl font-bold dark:text-white">{{ $pagesSettings->services_subheader }}</div>
            </div>
        </div>
        <div class="prose max-w-full">

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


    <section class="mt-12 flex" x-data="siteServiceFilter" >
        <div class="relative  min-h-screen w-84">
            <div class="absolute inset-0 bg-white flex flex-col">
                <flux:input x-model="searchterm" class="border-logo" icon:trailing="funnel" placeholder="Filtern Sie aus {{ count($services) }} Services"/>
                <div class="mt-4 pt-4 flex-grow relative">
                    <ul class="absolute inset-0 overflow-y-auto scrollbar scrollbar-thin  !list-none space-y-4">
                        <template x-for="service in services" :key="`menu-${service.id}`">
                            <li class="border border-logo rounded-lg p-2 !ml-0 relative group cursor-pointer" wire:click="set('tab', service.slug)">
                                <p class="!font-bold !mb-0 !dark:text-logo !text-logo !text-sm" x-text="service.name"></p>
                                <div class="flex space-x-2">
                                    <template x-for="point in service.points">
                                        <div class="flex space-x-px items-center !text-logo !dark:text-logo/50 !text-xs">
                                            <svg class="size-3" data-slot="icon" fill="none"
                                                 stroke-width="2" stroke="currentColor"
                                                 viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                            </svg>
                                            <span class="text-xs" x-text="point"></span>
                                        </div>
                                    </template>
                                </div>
                                <div class="absolute inset-0 rounded-lg bg-logo p-2 text-white "
                                     :class="selected == service.slug ? '' : 'translate-x-[98%] -scale-y-75 group-hover:scale-y-100 group-hover:translate-x-0 transition-all duration-500 ease-in'"
                                    >
                                    <p class="!font-bold !mb-0 !text-white !text-sm" x-text="service.name"></p>
                                    <div class="flex space-x-2">
                                        <template x-for="point in service.points">
                                            <div class="flex space-x-px items-center !text-white !text-xs">
                                                <svg class="size-3" data-slot="icon" fill="none"
                                                     stroke-width="2" stroke="currentColor"
                                                     viewBox="0 0 24 24"
                                                     xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                                </svg>
                                                <span class="text-xs" x-text="point"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
        <div class="p-4 prose" x-html="">

        </div>
    </section>
</x-section>
@script
<script>
    Alpine.data('siteServiceFilter', () => ({
        originalServices: @entangle('services'),
        services: null,
        searchterm: '',
        fuse: null,
        selected: @entangle('tab').live,
        selectedService: null,
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

            this.$watch('selected', (slug) => {
                this.selectedService = this.originalServices.find( service => service.slug == this.selected );
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