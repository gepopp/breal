<?php

namespace App\Filament\Widgets;

use App\Models\FAQ;
use App\Models\Question;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Storage;

class ExportFaqWidget extends Widget
{
    protected string $view = 'filament.widgets.export-faq-widget';

    protected int|string|array $columnSpan = 'full';

    public function export()
    {
        try {
            $fileName = 'faq-export-'.now()->format('Y-m-d_His').'.json';
            $filePath = 'exports/'.$fileName;

            // Export FAQs
            $faqs = FAQ::all()->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'slug' => $faq->slug,
                ];
            });

            // Export Questions
            $questions = Question::all()->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'answer' => $question->answer,
                    'company' => $question->company,
                    'slug' => $question->slug,
                ];
            });

            $data = [
                'faqs' => $faqs,
                'questions' => $questions,
                'exported_at' => now()->toIso8601String(),
                'total_count' => $faqs->count() + $questions->count(),
            ];

            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            Storage::disk('local')->put($filePath, $json);

            Notification::make()
                ->title('Export successful')
                ->body('FAQ and Questions exported successfully.')
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
