<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @fluxAppearance
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">


<livewire:parts.navbar/>

<div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
    <main class="flex flex-col w-full lg:max-w-4xl space-y-48">

        <div class="mt-48 flex flex-col items-center justify-center text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">Light</flux:radio>
                <flux:radio value="dark" icon="moon">Dark</flux:radio>
                <flux:radio value="system" icon="computer-desktop">System</flux:radio>
            </flux:radio.group>
        </div>


        <div class="flex flex-col items-center justify-center text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">

            <h1 class="!font-black lowercase text-5xl tracking-wider leading-16">
                <span class="bg-logo p-1.5 rounded text-white">Logo</span>
                Animation</h1>

            <p class="mt-16">Wir beim laden der Seiten abgespielt.</p>

            <div id="canvas"></div>
            <script type="module">
                lottie.loadAnimation({
                    container: document.getElementById('canvas'),
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    speed: .7,
                    path: '{{ asset('logo-auf-ebenen-fr-animation.json') }}'
                });
            </script>

        </div>

        <div class="flex flex-col items-center justify-center text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">

            <h1 class="!font-black lowercase text-5xl tracking-wider leading-16">
                <span class="bg-logo p-1.5 rounded text-white">menu</span>
                Animation
            </h1>

            <p class="mt-16">Hovern</p>

            <div class="relative w-full mt-16">
                <nav class="menu overflow-hidden text-xl font-bold text-logo/90">
                    <a href="#" class="menu-item">
                        <div>
                            <span class="menu-item-text">hausverwaltung</span>
                        </div>
                    </a>

                    <a href="#" class="menu-item">
                        <div>
                            <span class="menu-item-text">immobilien</span>
                        </div>
                    </a>

                    <a href="#" class="menu-item">
                        <div>
                            <span class="menu-item-text">technik</span>
                        </div>
                    </a>
                </nav>
            </div>
        </div>


        <div class="flex flex-col items-center justify-center text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">

            <h1 class="!font-black lowercase text-5xl tracking-wider leading-16">
                <span class="bg-logo p-1.5 rounded text-white">button</span>
                Animation
            </h1>

            <div class="mt-16">
                <a href="#" class="button-hover">
                    button
                    <span>
                    </span>
                    <div class="absolute inset-0 flex justify-center items-center">button</div>
                </a>
            </div>

        </div>


        <div class="flex flex-col items-center justify-center text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">

            <h1 class="!font-black lowercase text-5xl tracking-wider leading-16">
                <span class="bg-logo p-1.5 rounded text-white">text</span>
                Animation
            </h1>

            <div class="mt-16">
                <p class="text-lg font-light pb-4" data-aos="fade-up">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </p>
                <p class="text-lg font-light pb-4" data-aos="fade-up">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </p>
                <p class="text-lg font-light pb-4" data-aos="fade-up">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </p>
            </div>

        </div>


        <div class="flex flex-col items-center justify-center text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">

            <h1 class="!font-black lowercase text-5xl tracking-wider leading-16">
                <span class="bg-logo p-1.5 rounded text-white">demo</span>
                form
            </h1>

            <p class="mt-16">Demo-Formular zur Newsletter Anmeldung - versendet auch ein Demo-E-Mail</p>

            <livewire:demo-form/>

        </div>


    </main>
</div>
<livewire:footer/>


@fluxScripts




</body>
</html>
