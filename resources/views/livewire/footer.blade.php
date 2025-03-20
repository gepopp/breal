<?php

use function Livewire\Volt\{state};

state();

?>

<footer class="bg-logo-400 pt-24 flex flex-col items-center justify-center text-white px-4">
    <div class="lg:max-w-4xl xl:max-w-6xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 w-full">
        <div>
            <a href="{{ route('home') }}">
                <img src="{{ asset('be_Logo_RGB_white_400.svg') }}" class="h-16"/>
            </a>
            <div class="mt-12">
                <p>
                    Wir sind Ihr verlässlicher Partner in Wien und ganz Österreich, wenn es um die ganzheitliche Abwicklung und Betreuung Ihrer Immobilien geht.
                </p>
            </div>
        </div>


        <div class="flex md:justify-center">
            <nav class="font-semibold text-white flex flex-col space-y-6">
                <a href="#" class="uppercase cursor-pointer font-extrabold overflow-hidden text-2xl hover:tracking-wider transition-all duration-300">
                    verwaltung
                </a>

                <a href="#" class="uppercase cursor-pointer font-extrabold overflow-hidden text-2xl hover:tracking-wider transition-all duration-300">
                    immobilien
                </a>

                <a href="#" class="uppercase cursor-pointer font-extrabold overflow-hidden text-2xl hover:tracking-wider transition-all duration-300">
                    technik
                </a>

            </nav>
        </div>


        <div class="flex flex-col space-y-6">
            <p>Franz Josefs Kai 65, 1010 Wien</p>
            <p>Telefon: +43 1 535 36 19</p>
            <p>Fax: +43 1 535 64 28</p>
            <p>E-Mail: office@bontus-eybel.at</p>
            <p>MO-DO 	08:00-16:00
            <br>
            FR 	08:00-12:00</p>
        </div>
    </div>

    <div class="border-t border-white py-4 w-full mt-16 flex justify-center">
        <div class="lg:max-w-4xl xl:max-w-6xl w-full flex flex-col md:flex-row justify-between text-sm">
            <p class="!text-sm">&copy; {{ now()->format('Y') }} Bontus Eybel</p>
            <div class="flex justify-between md:space-x-6 lg:space-x-12 order-first md:order-last mb-4">
                <a href="#">Datenschutz</a>
                <a href="#">Barrierefreiheit</a>
                <a href="#">AGB</a>
            </div>
        </div>
    </div>

</footer>
