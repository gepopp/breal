<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CompaniesEnum: string implements HasLabel
{
    case Hausverwaltung = 'Hausverwaltung';
    case Makler = 'Makler';
    case Technik = 'Technik';
    public function getLabel(): ?string
    {
        return $this->name;
    }
}