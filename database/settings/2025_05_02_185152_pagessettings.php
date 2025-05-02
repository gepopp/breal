<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.search_header', 'home');
        $this->migrator->add('pages.search_subheader', 'Immobiliensuche der be real makler');
        $this->migrator->add('pages.search_introtext', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet hic incidunt, inventore ipsa iste minima nemo officia similique veniam vero. Aliquid aspernatur cumque dolore, eveniet facilis iusto laborum nihil similique?</p>');
    }
};
