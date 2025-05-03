<div>
    @if(!empty($titleimage))
        <div class="spotlight-group">
            <a class="spotlight relative group" href="{{ $titleimage }}">
                <img src="{{ $titleimage }}" class="w-full aspect-[4/3] object-cover image">
                <span class="absolute inset-0 flex items-center justify-center invisible group-hover:visible transition-all duration-300">
                    <svg class="size-8 text-white/90" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                    </svg>
                </span>
            </a>
        </div>
    @endif
</div>
@assets
<script src="https://rawcdn.githack.com/nextapps-de/spotlight/0.7.8/dist/spotlight.bundle.js"></script>
@endassets