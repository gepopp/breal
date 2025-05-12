<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.makler_contactform_email', 'office@bontus-eybel.at');
        $this->migrator->add('pages.makler_contactform_phone', '+43 1 535 36 19');
        $this->migrator->add('pages.makler_contactform_address', 'Franz Josefs Kai 65, 1010 Wien');
        $this->migrator->add('pages.technik_contactform_email', 'office@bontus-eybel.at');
        $this->migrator->add('pages.technik_contactform_phone', '+43 1 535 36 19');
        $this->migrator->add('pages.technik_contactform_address', 'Franz Josefs Kai 65, 1010 Wien');
    }
};
