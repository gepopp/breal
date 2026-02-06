<?php

namespace App\Filament\Resources\ContactpersonResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ContactpersonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactpeople extends ListRecords
{
    protected static string $resource = ContactpersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
