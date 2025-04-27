<div class="absolute bg-white top-full left-0 min-w-full mt-12">
    <div class="relative bg-white min-w-full w-full shadow z-[9999]">
        <div class="relative  min-h-[400px] w-84">
            <div x-data="menuServiceFilter"
                 class="absolute inset-0 bg-white -mt-12 p-6 flex flex-col">
                <flux:input x-model="searchterm" class="border-logo" icon:trailing="funnel"
                            placeholder="Filtern Sie aus {{ count($services) }} Services"/>

                <div class="border-t border-logo mt-4 pt-4 flex-grow relative">
                    <ul class="absolute inset-0 overflow-y-auto scrollbar scrollbar-thin  !list-none">
                        <template x-for="service in services" :key="`menu-${service.id}`">
                            <li class="border-b border-logo p-2 !ml-0">
                                <p class="!font-bold !mb-0 !dark:text-logo !text-logo !text-sm" x-text="service.name"></p>
                                <div class="flex space-x-2">
                                    <template x-for="point in service.points">
                                        <div class="flex space-x-px items-center !text-logo !dark:text-logo/50 !text-xs">
                                            <svg class="size-3" data-slot="icon" fill="none"
                                                 stroke-width="2" stroke="currentColor"
                                                 viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 4.5v15m7.5-7.5h-15"></path>
                                            </svg>
                                            <span class="text-xs" x-text="point"></span>
                                        </div>
                                    </template>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    Alpine.data('menuServiceFilter', () => ({
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