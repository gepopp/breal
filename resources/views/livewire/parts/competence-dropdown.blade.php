<div class="absolute bg-white top-full left-0 min-w-full">
    <div class="relative bg-white min-w-full w-full shadow z-[9999] p-4">
        <ul class="w-84">
            @foreach($competences as $competence)
                <li class="border-b border-logo p-2 !ml-0">
                    <a href="{{ route( strtolower($company . '.leistung' ), $competence) }}" wire:navigate class="block text-sm text-gray-700 hover:bg-gray-100 px-2">
                        <p class="line-clamp-1 !font-bold !text-base">{{ $competence->name }}</p>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 pt-4 border-t border-logo">
            <x-button href="{{ route('verwalterservice') }}">{{ __('Corporate Service') }}</x-button>
        </div>
    </div>
</div>