<?php

namespace App\Filament\Resources\ContactpersonResource\Pages;

use App\Filament\Resources\ContactpersonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactpeople extends ListRecords
{
    protected static string $resource = ContactpersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
