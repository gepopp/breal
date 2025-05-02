<?php

namespace App\Filament\Resources\RealtyResource\Pages;

use App\Filament\Resources\RealtyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRealties extends ListRecords
{
    protected static string $resource = RealtyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
