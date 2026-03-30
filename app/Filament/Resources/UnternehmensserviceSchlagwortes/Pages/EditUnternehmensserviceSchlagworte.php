<?php

namespace App\Filament\Resources\UnternehmensserviceSchlagwortes\Pages;

use App\Filament\Resources\UnternehmensserviceSchlagwortes\UnternehmensserviceSchlagworteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUnternehmensserviceSchlagworte extends EditRecord
{
    protected static string $resource = UnternehmensserviceSchlagworteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
