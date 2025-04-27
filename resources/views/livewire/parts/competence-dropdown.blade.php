<div class="absolute bg-white top-full left-0 min-w-full">
    <div class="relative bg-white min-w-full w-full shadow z-[9999]">
        <ul class="w-84 p-4 space-y-2">
            @foreach($competences as $competence)
                <li>
                    <a href="{{ route('leistung', $competence) }}" wire:navigate class="block text-sm text-gray-700 hover:bg-gray-100 px-2">
                        <p class="line-clamp-1 !font-medium !text-base">{{ $competence->name }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>