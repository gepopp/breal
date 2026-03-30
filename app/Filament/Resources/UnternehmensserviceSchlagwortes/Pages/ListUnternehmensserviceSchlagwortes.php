<?php

namespace App\Filament\Resources\UnternehmensserviceSchlagwortes\Pages;

use App\Filament\Resources\UnternehmensserviceSchlagwortes\UnternehmensserviceSchlagworteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUnternehmensserviceSchlagwortes extends ListRecords
{
    protected static string $resource = UnternehmensserviceSchlagworteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
