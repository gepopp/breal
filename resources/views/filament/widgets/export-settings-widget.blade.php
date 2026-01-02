<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Settings Export
        </x-slot>

        <x-slot name="description">
            Export all translatable text from Spatie settings to JSON format
        </x-slot>

        <div class="space-y-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p class="mb-2">This will export the following settings classes:</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>LandingpageHausverwaltung (28 text properties)</li>
                    <li>LandingpageTechnikSettings (13 text properties)</li>
                    <li>LandingpageMaklerSettings (11 text properties)</li>
                    <li>PagesSettings (43 text properties)</li>
                </ul>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-500">
                    Only translatable text properties will be exported. Images, configuration values, and non-text data are excluded.
                </p>
            </div>

            <div class="flex justify-end">
                <x-filament::button
                    wire:click="export"
                    icon="heroicon-o-arrow-down-tray"
                    color="primary"
                >
                    Export Settings
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>

    @script
    <script>
        $wire.on('download-file', (event) => {
            console.log('Download event received:', event);
            const url = event.url || event[0]?.url;

            if (url) {
                console.log('Downloading from:', url);
                window.location.href = url;
            } else {
                console.error('No URL provided in download event');
            }
        });
    </script>
    @endscript
</x-filament-widgets::widget>
