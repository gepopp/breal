<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\Route;

enum CompaniesEnum: string implements HasLabel
{
    case Hausverwaltung = 'Hausverwaltung';
    case Makler = 'Makler';
    case Technik = 'Technik';
    public function getLabel(): ?string
    {
        return $this->name;
    }


    public function getByRoute()
    {
        if(Route::is('hausverwaltung.*'))
        {
           return self::Hausverwaltung;
        }

        if(Route::is('immobilien.*'))
        {
            return self::Makler;
        }

        if(Route::is('technik.*'))
        {
            return self::Technik;
        }

        return null;
    }
}