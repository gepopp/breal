<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('LandingpageTechnik.hero_image', NULL);
        $this->migrator->add('LandingpageTechnik.hero_image_alt', 'Bontus Eybel Intro Bild');
        $this->migrator->add('LandingpageTechnik.hero_header', 'engineering');
        $this->migrator->add('LandingpageTechnik.hero_subheader', 'Die Technik für Hausverwaltung und Immobilienmanagement');
        $this->migrator->add('LandingpageTechnik.hero_introtext', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur delectus ea id maiores quasi reiciendis sed voluptate. Cumque deserunt dignissimos, dolore, excepturi fugiat id libero nostrum qui, quod repellat vero.</p>');
    }
};
