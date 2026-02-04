<?php

namespace App\Filament\Widgets;

use App\Models\Competence;
use App\Models\Reference;
use App\Models\Service;
use App\Models\Timeline;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Storage;

class ExportContentWidget extends Widget
{
    protected string $view = 'filament.widgets.export-content-widget';

    protected int|string|array $columnSpan = 'full';

    public function export()
    {
        try {
            $fileName = 'content-export-'.now()->format('Y-m-d_His').'.json';
            $filePath = 'exports/'.$fileName;

            // Export Competences
            $competences = Competence::all()->map(function ($comp) {
                return [
                    'id' => $comp->id,
                    'name' => $comp->name,
                    'description' => $comp->description,
                    'body' => $comp->body,
                    'company' => $comp->company,
                    'slug' => $comp->slug,
                ];
            });

            // Export Services
            $services = Service::all()->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'points' => $service->points,
                    'company' => $service->company,
                    'slug' => $service->slug,
                ];
            });

            // Export References
            $references = Reference::all()->map(function ($ref) {
                return [
                    'id' => $ref->id,
                    'title' => $ref->title,
                    'description' => $ref->description,
                    'client_name' => $ref->client_name,
                    'testimonial' => $ref->testimonial,
                    'slug' => $ref->slug,
                ];
            });

            // Export Timeline
            $timeline = Timeline::all()->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'year' => $event->year,
                ];
            });

            $data = [
                'competences' => $competences,
                'services' => $services,
                'references' => $references,
                'timeline' => $timeline,
                'exported_at' => now()->toIso8601String(),
                'total_count' => $competences->count() + $services->count() + $references->count() + $timeline->count(),
            ];

            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            Storage::disk('local')->put($filePath, $json);

            Notification::make()
                ->title('Export successful')
                ->body('Content exported successfully.')
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
