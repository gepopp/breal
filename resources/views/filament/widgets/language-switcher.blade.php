<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-filament::icon
                    icon="heroicon-o-language"
                    class="h-5 w-5 text-gray-500 dark:text-gray-400"
                />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                    {{ __('Frontend Language') }}
                </span>
            </div>

            <div class="flex gap-2">
                <x-filament::button
                    wire:click="switchLanguage('de')"
                    :color="$this->getCurrentLocale() === 'de' ? 'primary' : 'gray'"
                    size="sm"
                >
                    Deutsch
                </x-filament::button>

                <x-filament::button
                    wire:click="switchLanguage('en')"
                    :color="$this->getCurrentLocale() === 'en' ? 'primary' : 'gray'"
                    size="sm"
                >
                    English
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
