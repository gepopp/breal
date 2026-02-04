<?php

namespace App\Filament\Widgets;

use App\Models\Contactperson;
use App\Models\Department;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Storage;

class ExportOrganizationWidget extends Widget
{
    protected string $view = 'filament.widgets.export-organization-widget';

    protected int|string|array $columnSpan = 'full';

    public function export()
    {
        try {
            $fileName = 'organization-export-'.now()->format('Y-m-d_His').'.json';
            $filePath = 'exports/'.$fileName;

            // Export Contact Persons
            $contactPersons = Contactperson::all()->map(function ($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->name,
                    'position' => $person->position,
                    'email' => $person->email,
                    'phone' => $person->phone,
                    'department_id' => $person->department_id,
                ];
            });

            // Export Departments
            $departments = Department::all()->map(function ($dept) {
                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'company' => $dept->company,
                    'parent_department_id' => $dept->department_id,
                ];
            });

            $data = [
                'contact_persons' => $contactPersons,
                'departments' => $departments,
                'exported_at' => now()->toIso8601String(),
                'total_count' => $contactPersons->count() + $departments->count(),
            ];

            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            Storage::disk('local')->put($filePath, $json);

            Notification::make()
                ->title('Export successful')
                ->body('Organization data exported successfully.')
                ->success()
                ->send();

            $this->dispatch('download-file', [
                'url' => route('download-settings-export', ['filename' => $fileName]),
            ]);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Export failed')
                ->body('Error: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }
}
