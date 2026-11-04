{{-- The top padding (header padding 16px + border 2px + 1px) lets the white box start below the header border and keeps the hover area connected to the menu item. --}}
<div class="absolute top-full left-0 min-w-full pt-[19px]">
    {{-- Same box, list and hover styling as menu-service-dropdown.blade.php. --}}
    <div class="relative bg-white w-84 shadow z-[9999] p-6">
        <ul class="!list-none">
            @foreach($competences as $competence)
                <li class="border-b border-logo !ml-0">
                    <a href="{{ route( strtolower($company . '.leistung' ), $competence) }}" wire:navigate class="block p-2 hover:bg-gray-100">
                        <p class="!font-bold !mb-0 !text-logo !text-sm">{{ $competence->name }}</p>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 pt-4 border-t border-logo">
            <x-button href="{{ route('verwalterservice') }}">{{ __('Corporate Service') }}</x-button>
        </div>
    </div>
</div>