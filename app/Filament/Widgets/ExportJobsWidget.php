<?php

namespace App\Filament\Widgets;

use App\Models\JobVacancy;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Storage;

class ExportJobsWidget extends Widget
{
    protected string $view = 'filament.widgets.export-jobs-widget';

    protected int|string|array $columnSpan = 'full';

    public function export()
    {
        try {
            $fileName = 'jobs-export-'.now()->format('Y-m-d_His').'.json';
            $filePath = 'exports/'.$fileName;

            $jobs = JobVacancy::all()->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'job_title' => $job->job_title,
                    'description' => $job->description,
                    'unit_text' => $job->unit_text,
                    'company' => $job->company,
                    'slug' => $job->slug,
                ];
            });

            $data = [
                'job_vacancies' => $jobs,
                'exported_at' => now()->toIso8601String(),
                'total_count' => $jobs->count(),
            ];

            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            Storage::disk('local')->put($filePath, $json);

            Notification::make()
                ->title('Export successful')
                ->body('Job vacancies exported successfully.')
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
