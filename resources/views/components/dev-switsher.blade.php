<div>
    @env(['local', 'development'])
        <div class="fixed bottom-0 right-0 text-5xl p-4">
            <div x-data class="z-[9999] relative">
                <p x-on:click="$flux.dark = ! $flux.dark" class="!text-xs relative z-[9999]">Dark/Light</p>
            </div>
            <span class="sm:hidden">xs</span>
            <span class="hidden sm:block md:hidden">sm</span>
            <span class="hidden md:block lg:hidden">md</span>
            <span class="hidden lg:block xl:hidden">lg</span>
            <span class="hidden xl:block 2xl:hidden">xl</span>
            <span class="hidden 2xl:block">2xl</span>
        </div>
    @endenv
    <div class="leading-[65px]"></div>
</div>