<?php

namespace App\Filament\Resources\ContactpersonResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ContactpersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactperson extends EditRecord
{
    protected static string $resource = ContactpersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
