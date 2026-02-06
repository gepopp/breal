<?php

namespace App\Filament\Resources\ContactRequestResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ContactRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactRequests extends ListRecords
{
    protected static string $resource = ContactRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
