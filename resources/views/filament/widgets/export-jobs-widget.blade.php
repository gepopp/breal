<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Job Vacancies Export
        </x-slot>

        <x-slot name="description">
            Export all job vacancy listings with translatable text
        </x-slot>

        <div class="space-y-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p class="mb-2">This will export:</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>Job title and position</li>
                    <li>Job description</li>
                    <li>Salary unit text</li>
                    <li>Company information</li>
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
                    Export Job Vacancies
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
