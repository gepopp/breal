<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.vacancies_header', 'with us');
        $this->migrator->add('pages.vacancies_subheader', 'Werde Teil des BeReal Teams');
    }
};
