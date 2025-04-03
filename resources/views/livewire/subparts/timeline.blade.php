<div class="my-12 relative">
    <div x-data>
        <ul x-ref="list" class="grid grid-cols-1 lg:grid-cols-2 lg:gap-12 max-h-[600px] overflow-y-auto pb-48 scroll-smooth">
            @foreach($timeline as $entry)
                <li @class(['flex space-x-4 py-4 border-b-2 border-logo'])>
                    <div class="flex justify-center items-center">
                        <p @class(['bg-logo text-white !text-3xl !font-bold font-extrabold leading-[32px] px-1.5 align-baseline rounded tracking-wide'])>{{ $entry->year }}</p>
                    </div>
                    <div>
                        <h5 class="font-bold text-xl">{{ $entry->title }}</h5>
                        <p>{{ $entry->description }}</p>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="absolute w-full h-48 bottom-0 left-0 bg-linear-to-b from-transparent via-white to-white flex justify-center items-end pb-24">
            <svg class="size-12 text-logo cursor-pointer pointer-events-auto" x-on:click="$refs.list.scrollTop = 0" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 18.75 7.5-7.5 7.5 7.5"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 7.5-7.5 7.5 7.5"></path>
            </svg>
        </div>
    </div>
</div>
