<div>
    @env(['local', 'development'])
        <div class="fixed bottom-0 right-0 text-5xl p-4">
            <div x-data>
                <p x-on:click="$flux.dark = ! $flux.dark" class="!text-xs">Dark/Light</p>
            </div>
            <p class="sm:hidden">xs</p>
            <p class="hidden sm:block md:hidden">sm</p>
            <p class="hidden md:block lg:hidden">md</p>
            <p class="hidden lg:block xl:hidden">lg</p>
            <p class="hidden xl:block 2xl:hidden">xl</p>
            <p class="hidden 2xl:block">2xl</p>
        </div>
    @endenv
</div>