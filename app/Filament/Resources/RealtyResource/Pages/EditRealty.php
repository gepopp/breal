<?php

namespace App\Filament\Resources\RealtyResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\RealtyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRealty extends EditRecord
{
    protected static string $resource = RealtyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
