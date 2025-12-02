<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Step 1: Check ZIP File --}}
        <x-filament::section>
            <x-slot name="heading">
                Schritt 1: ZIP-Datei prüfen
            </x-slot>

            @if($zipFileInfo)
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-success-500"/>
                        <span class="font-semibold">ZIP-Datei gefunden</span>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-2">
                        <div><strong>Dateiname:</strong> {{ $zipFileInfo['filename'] }}</div>
                        <div><strong>Größe:</strong> {{ $zipFileInfo['size_formatted'] }}</div>
                        <div><strong>Geändert:</strong> {{ $zipFileInfo['modified_date'] }}</div>
                    </div>

                    <x-filament::button
                        wire:click="extractXmlAction"
                        color="primary"
                        icon="heroicon-o-arrow-right"
                    >
                        Weiter zu Schritt 2: XML extrahieren
                    </x-filament::button>
                </div>
            @else
                <div class="flex items-center space-x-2 text-gray-500">
                    <x-heroicon-o-x-circle class="w-6 h-6"/>
                    <span>Keine ZIP-Datei gefunden (imports/openimmo.zip)</span>
                </div>
            @endif
        </x-filament::section>

        {{-- Step 2: Extract XML --}}
        <x-filament::section>
            <x-slot name="heading">
                Schritt 2: XML extrahieren
            </x-slot>

            @if($extractedXmlInfo)
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-success-500"/>
                        <span class="font-semibold">XML extrahiert</span>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-2">
                        <div><strong>Pfad:</strong> {{ $extractedXmlInfo['path'] }}</div>
                        <div><strong>Größe:</strong> {{ $extractedXmlInfo['size_formatted'] }}</div>
                        <div class="mt-4">
                            <strong>Vorschau:</strong>
                            <pre class="mt-2 text-xs bg-gray-100 dark:bg-gray-900 p-3 rounded overflow-x-auto">{{ $extractedXmlInfo['preview'] }}...</pre>
                        </div>
                    </div>

                    <x-filament::button
                        wire:click="extractPropertiesAction"
                        color="primary"
                        icon="heroicon-o-arrow-right"
                    >
                        Weiter zu Schritt 3: Immobilien extrahieren
                    </x-filament::button>
                </div>
            @else
                <div class="text-gray-500">
                    Noch nicht ausgeführt
                </div>
            @endif
        </x-filament::section>

        {{-- Step 3: Extract Properties --}}
        <x-filament::section>
            <x-slot name="heading">
                Schritt 3: Immobilien-Dateien extrahieren
            </x-slot>

            @if($batchFilesInfo)
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-success-500"/>
                        <span class="font-semibold">{{ $batchFilesInfo['count'] }} Immobilien-Dateien extrahiert</span>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <strong>Beispiel-Dateien:</strong>
                        <ul class="mt-2 space-y-1 text-sm">
                            @foreach($batchFilesInfo['sample'] as $file)
                                <li>{{ $file['filename'] }} ({{ round($file['size'] / 1024, 2) }} KB)</li>
                            @endforeach
                        </ul>
                    </div>

                    <x-filament::button
                        wire:click="truncateTableAction"
                        color="danger"
                        icon="heroicon-o-trash"
                    >
                        Weiter zu Schritt 4: Datenbank leeren
                    </x-filament::button>
                </div>
            @else
                <div class="text-gray-500">
                    Noch nicht ausgeführt
                </div>
            @endif
        </x-filament::section>

        {{-- Step 4: Truncate Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Schritt 4: Datenbank leeren
            </x-slot>

            @if($tableCleared)
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-success-500"/>
                        <span class="font-semibold">Datenbank geleert</span>
                    </div>

                    <x-filament::button
                        wire:click="importPropertiesAction"
                        color="success"
                        icon="heroicon-o-arrow-down-tray"
                    >
                        Weiter zu Schritt 5: Import starten
                    </x-filament::button>
                </div>
            @else
                <div class="text-gray-500">
                    Noch nicht ausgeführt
                </div>
            @endif
        </x-filament::section>

        {{-- Step 5: Import Properties --}}
        <x-filament::section>
            <x-slot name="heading">
                Schritt 5: Immobilien importieren
            </x-slot>

            @if($importCompleted)
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-check-circle class="w-6 h-6 text-success-500"/>
                        <span class="font-semibold">Import gestartet - {{ $propertiesCount }} Jobs in der Queue</span>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start space-x-2">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5"/>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                Die Immobilien werden jetzt im Hintergrund importiert. Dies kann einige Minuten dauern.
                                Überprüfen Sie die Queue und die Logs für den Fortschritt.
                            </div>
                        </div>
                    </div>

                    <x-filament::button
                        wire:click="cleanupAction"
                        color="warning"
                        icon="heroicon-o-trash"
                    >
                        Temporäre Dateien aufräumen
                    </x-filament::button>
                </div>
            @else
                <div class="text-gray-500">
                    Noch nicht ausgeführt
                </div>
            @endif
        </x-filament::section>

        {{-- Info Box --}}
        <x-filament::section>
            <x-slot name="heading">
                Hinweise
            </x-slot>

            <div class="space-y-2 text-sm">
                <p>Dieser Import-Prozess führt Sie Schritt für Schritt durch den Justimmo-Import:</p>
                <ol class="list-decimal list-inside space-y-1 ml-2">
                    <li>Prüft ob eine neue openimmo.zip Datei vorhanden ist</li>
                    <li>Extrahiert die XML-Datei aus dem ZIP</li>
                    <li>Teilt die große XML in einzelne Immobilien-Dateien auf</li>
                    <li>Leert die Datenbank (alle alten Einträge werden gelöscht)</li>
                    <li>Importiert alle Immobilien über die Queue</li>
                    <li>Räumt temporäre Dateien auf</li>
                </ol>
                <p class="mt-4 text-amber-600 dark:text-amber-400">
                    <strong>Achtung:</strong> Der Import löscht alle bestehenden Immobilien-Daten!
                </p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
