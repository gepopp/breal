<x-layouts.site xmlns:flus="http://www.w3.org/1999/html">
    <div class="grid grid-cols-1 lg:grid-cols-2 w-full">
        <div class="flex justify-center items-center min-h-[70vh]">
            <div class="max-w-md">
                <x-headings>
                    <x-slot name="tag">solved</x-slot>
                    Neue Kontaktanfrage als gelöst markiert!
                </x-headings>

                <ul>
                    <li>{{ $request->firstname }} {{ $request->lastname }}</li>
                    <li>{{ $request->email }}</li>
                    <li>{{ $request->phone }}</li>
                    <li>{!! $request->message !!}</li>
                    <li>{{ $request->created_at->format('d.m.Y H:i') }}</li>
                </ul>
                @if($request->hasMedia('*'))
                    <h3 class="my-4 text-2xl font-bold">Dateien</h3>
                    <ul>
                        @foreach($request->getMedia('*') as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" download class="flex items-center space-x-2 cursor-pointer">
                                <svg class="size-6" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                                </svg>
                                <span class="underline">{{ $media->file_name }}</span>
                            </a>
                        @endforeach
                    </ul>

                @endif

                <div class="mt-8 space-y-8">
                    <x-button href="/admin" class="w-full">backend</x-button>
                </div>
            </div>
        </div>
        <div class="relative order-first lg:order-last">

            <video class="h-full min-w-full w-auto object-cover" muted autoplay loop>
                <source src="{{ asset('404-man.webm') }}" type="video/webm"/>
            </video>
            {{--                <img src="{{ asset('404-bg.jpg') }}" class="h-full min-w-full w-auto object-cover"/>--}}

            <div class="absolute top-0 left-0 h-full">
                <svg fill="currentColor" class="text-white h-full" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 97.7 500">
                    <polygon class="st0" points="0 500 0 500 0 0 94.9 0 0 500"/>
                </svg>
            </div>
        </div>
    </div>
</x-layouts.site>