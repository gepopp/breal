<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('ansehen')
            ->openUrlInNewTab()
            ->url(fn($record) => route('faq.single', $record->slug))
        ];
    }
}
