<x-site xmlns:flus="http://www.w3.org/1999/html">
        <div class="grid grid-cols-1 lg:grid-cols-2 w-full">
            <div class="flex justify-center items-center min-h-[70vh]">
                <div class="max-w-md">
                    <x-headings>
                        <x-slot name="tag">unfound</x-slot>
                        Diesen Inhalt konnten wir leider nicht finden
                    </x-headings>
                    <p>Bitte prüfen Sie die Eingabe in der Adressleiste Ihres Browsers auf Tippfehler oder besuchen Sie eine unserer Unternehmensbereiche:</p>

                    <div class="mt-8 space-y-8">
                        <x-button class="w-full">Hausverwaltung</x-button>
                        <x-button class="w-full">Immobilien</x-button>
                        <x-button class="w-full">Technik</x-button>
                    </div>
                </div>
            </div>
            <div class="relative order-first lg:order-last">

                <video  class="h-full min-w-full w-auto object-cover" muted autoplay loop>
                    <source src="{{ asset('404-man.webm') }}" type="video/webm" />
                </video>
{{--                <img src="{{ asset('404-bg.jpg') }}" class="h-full min-w-full w-auto object-cover"/>--}}

                <div class="absolute top-0 left-0 h-full">
                    <svg fill="currentColor" class="text-white h-full" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 97.7 500">
                        <polygon class="st0" points="0 500 0 500 0 0 94.9 0 0 500"/>
                    </svg>
                </div>
            </div>
        </div>
</x-site>