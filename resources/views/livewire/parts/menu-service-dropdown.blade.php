{{-- The top padding (header padding 16px + border 2px + 1px) lets the white box start below the header border and keeps the hover area connected to the menu item. --}}
<div class="absolute top-full left-0 min-w-full pt-[19px]">
    {{-- Same box, list and hover styling as competence-dropdown.blade.php. --}}
    <div x-data="menuServiceFilter({{ Js::from(array_map(fn (array $item): array => ['id' => $item['id'], 'name' => $item['name']], $items)) }})"
         class="relative bg-white w-84 shadow z-[9999] p-6">
        @if(count($items) >= config('company.service_filter_min_items'))
            <flux:input x-model="searchterm" class="border-logo mb-4" icon:trailing="funnel"
                        placeholder="{{ __('service.filter_placeholder', ['count' => count($items)]) }}"/>
        @endif

        <ul class="max-h-[22rem] overflow-y-auto scrollbar scrollbar-thin !list-none">
            @foreach($items as $item)
                <li class="border-b border-logo !ml-0" x-show="visibleIds.includes({{ $item['id'] }})">
                    <a href="{{ $item['link'] }}" class="block p-2 hover:bg-gray-100">
                        <p class="!font-bold !mb-0 !text-logo !text-sm">{{ $item['name'] }}</p>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 pt-4 border-t border-logo">
            <x-button href="https://realonline.bereal-immobilien.at/">{{ __('navigation.customer_login') }}</x-button>
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
