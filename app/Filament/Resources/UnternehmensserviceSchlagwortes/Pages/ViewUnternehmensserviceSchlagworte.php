<?php

namespace App\Filament\Resources\UnternehmensserviceSchlagwortes\Pages;

use App\Filament\Resources\UnternehmensserviceSchlagwortes\UnternehmensserviceSchlagworteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUnternehmensserviceSchlagworte extends ViewRecord
{
    protected static string $resource = UnternehmensserviceSchlagworteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
