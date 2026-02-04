<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Widget
{
    protected string $view = 'filament.widgets.language-switcher';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -1;

    public function getCurrentLocale(): string
    {
        return session('locale', config('app.locale'));
    }

    public function switchLanguage(string $locale): void
    {
        session()->put('locale', $locale);
        App::setLocale($locale);
        $this->redirect(request()->header('Referer'));
    }
}
