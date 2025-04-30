<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('LandingpageTechnik.about_header', 'with us');
        $this->migrator->add('LandingpageTechnik.about_subheader', 'Warum be real?');
        $this->migrator->add('LandingpageTechnik.about_text', '<p>Als Familienunternehmen wissen wir, dass viele Menschen unter einem Dach auch viele Meinungen bedeuten können. Nicht nur das, auch die individuellen Bedürfnisse und Wünsche an eine Immobilie können höchst unterschiedlich sein.</p><p>Unser Ansatz: wir verknüpfen Tradition und Moderne sowie unsere Menschenkenntnis, um die passenden Lösungen zu finden. Denn für uns ist Hausverwaltung eine Herzensangelegenheit, die wir mit Engagement und Begeisterung in jedem Projekt leben.</p>');
        $this->migrator->add('LandingpageTechnik.about_image', 0);
        $this->migrator->add('LandingpageTechnik.about_image_alt', 'Bontus Eybel Intro Bild');
        $this->migrator->add('LandingpageTechnik.about_video_embed_code', '');
    }
};
