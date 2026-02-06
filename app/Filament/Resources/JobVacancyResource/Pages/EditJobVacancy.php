<?php

namespace App\Filament\Resources\JobVacancyResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\JobVacancyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobVacancy extends EditRecord
{
    protected static string $resource = JobVacancyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
