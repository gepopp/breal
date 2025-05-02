<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\Request;
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


    public static function getByRoute()
    {
        if(Request::routeIs('hausverwaltung.*'))
        {
           return self::Hausverwaltung->name;
        }

        if(Request::routeIs('makler.*'))
        {
            return self::Makler->name;
        }

        if(Request::routeIs('technik.*'))
        {
            return self::Technik->name;
        }

        return self::Hausverwaltung->name;
    }
}