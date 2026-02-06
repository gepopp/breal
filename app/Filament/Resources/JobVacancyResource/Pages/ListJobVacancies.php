<?php

namespace App\Filament\Resources\JobVacancyResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\JobVacancyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobVacancies extends ListRecords
{
    protected static string $resource = JobVacancyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
