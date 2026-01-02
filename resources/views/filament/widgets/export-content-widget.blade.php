<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Content Export
        </x-slot>

        <x-slot name="description">
            Export Competences, Services, References, and Timeline with translatable text
        </x-slot>

        <div class="space-y-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p class="mb-2">This will export:</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>Competences (name, description, body)</li>
                    <li>Services (name, description, points)</li>
                    <li>References (title, description, testimonials)</li>
                    <li>Timeline events (title, description)</li>
                </ul>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-500">
                    Includes all text fields suitable for translation.
                </p>
            </div>

            <div class="flex justify-end">
                <x-filament::button
                    wire:click="export"
                    icon="heroicon-o-arrow-down-tray"
                    color="primary"
                >
                    Export Content
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>

    @script
    <script>
        $wire.on('download-file', (event) => {
            const url = event.url || event[0]?.url;
            if (url) {
                window.location.href = url;
            }
        });
    </script>
    @endscript
</x-filament-widgets::widget>
