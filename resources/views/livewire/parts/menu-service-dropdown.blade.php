<div class="absolute bg-white top-full left-0 min-w-full mt-12">
    <div class="relative bg-white min-w-full w-full shadow z-[9999]">
        <div class="relative  min-h-[400px] w-84">
            <div x-data="menuServiceFilter({{ Js::from(array_map(fn (array $item): array => ['id' => $item['id'], 'name' => $item['name']], $items)) }})"
                 class="absolute inset-0 bg-white -mt-12 p-6 flex flex-col">
                @if(count($items) >= config('company.service_filter_min_items'))
                    <flux:input x-model="searchterm" class="border-logo" icon:trailing="funnel"
                                placeholder="{{ __('service.filter_placeholder', ['count' => count($items)]) }}"/>
                @endif

                <div @class(['flex-grow relative', 'border-t border-logo mt-4 pt-4' => count($items) >= config('company.service_filter_min_items')])>
                    <ul class="absolute inset-0 overflow-y-auto scrollbar scrollbar-thin  !list-none">
                        @foreach($items as $item)
                            <li class="border-b border-logo p-2 !ml-0" x-show="visibleIds.includes({{ $item['id'] }})">
                                <a href="{{ $item['link'] }}">
                                    <p class="!font-bold !mb-0 !dark:text-logo !text-logo !text-sm">{{ $item['name'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4 pt-4 border-t border-logo">
                    <x-button href="https://realonline.bereal-immobilien.at/">{{ __('navigation.customer_login') }}</x-button>
                </div>

            </div>
        </div>
    </div>
</div>
@script
<script>
    Alpine.data('menuServiceFilter', (items) => ({
        items: items,
        visibleIds: items.map(item => item.id),
        searchterm: '',
        fuse: null,
        init() {
            this.fuse = new window.Fuse(this.items,
                {
                    keys: ['name'],
                    threshold: 0.4
                });

            this.$watch('searchterm', () => {
                this.filterServices();
            })
        },
        filterServices() {
            if (this.searchterm.length < 3) {
                this.visibleIds = this.items.map(item => item.id);
            } else {
                this.visibleIds = this.fuse.search(this.searchterm).map(result => result.item.id);
            }
        }
    }))
</script>
@endscript
