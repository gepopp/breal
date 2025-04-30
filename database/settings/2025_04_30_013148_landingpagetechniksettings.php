<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('LandingpageTechnik.intro_layout', 'two_columns');
        $this->migrator->add('LandingpageTechnik.hero_text_column_one', '<p>Unser Ziel ist es, den Wert jeder Immobilie zu bewahren, aber auch zu steigern, indem wir sie mit Sorgfalt und Hingabe pflegen. Wir streben jeden Tag danach, die Erwartungen unserer Kunden zu übertreffen. Wenn unsere Arbeit so nahtlos verläuft, dass niemand merkt, dass wir im Hintergrund agieren, dann wissen wir, dass wir unsere Aufgabe erfolgreich erfüllt haben. Wir setzen uns mit Begeisterung dafür ein, dass die uns anvertraute Immobilie nicht nur rundum betreut, sondern auch verwaltet und schlussendlich zeitgemäß gemanaged wird.</p>');
        $this->migrator->add('LandingpageTechnik.hero_text_column_two', '<p>Sollte aber doch mal wo der Haken drin sein, dann sind wir zur Stelle und bereit jedes Problem zu lösen: von der störrischen Glühbirne über jegliche Schadensfälle bis hin zu komplexen Sanierungen - wir nehmen uns Ihrer Problemen an!</p><p>Es erfüllt uns mit Freude, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden!</p>');
        $this->migrator->add('LandingpageTechnik.text', '<p>Und nur dann ist auch die entsprechende Erhaltung des Wertes von Gebäuden sichergestellt. Immobilien wollen gehegt und gepflegt werden, und die Nutzerinnen und Nutzer jeden Tag aufs Neue zufriedengestellt werden. Am liebsten ist es uns eigentlich, wenn man nie mit uns zu tun hat, dann haben wir Vieles richtig gemacht.</p><p>Wenn doch irgendwo der Haken drin ist, dann lieben wir es, Problemlöser zu sein - egal ob es um eine Glühlampe oder eine umfangreiche Sanierung geht.</p><p>Es erfüllt uns mit Freude, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden!</p>');
        $this->migrator->add('LandingpageTechnik.text_image', 0);
        $this->migrator->add('LandingpageTechnik.text_image_alt', 'Bontus Eybel Intro Bild');
    }
};
