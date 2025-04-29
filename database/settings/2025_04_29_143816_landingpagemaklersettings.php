<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('MaklerSettings.about_text', '<p>Im Frühjahr 2021 gründete Tanja Bachschwöller mit ihrem Lebensgefährten Lukas Eybel die Immobilienvermittlung BE Real Immobilien. Jahrelange Berufserfahrung, Aneignung von fundiertem Wissen, sowie das facheinschlägige Studium „Real Estate Management“ an einer renommierten Universität in Österreich spiegeln sich in der maßgeschneiderten Beratung und dem charmanten Umgang mit Kunden wider. Aufgrund der hervorragenden Vernetzung in der Branche sowie der Zusammenarbeit mit mehreren Kooperationspartnern bietet sie jedem Immobiliensuchenden eine einwandfreie Abwicklung bei der Anmietung oder beim Kauf einer Immobilie.</p>');
        $this->migrator->add('MaklerSettings.about_image', 0);
        $this->migrator->add('MaklerSettings.about_image_alt', 'Geschäftsführerin und Eigentümerin Tanja Bachschwöller, BA');
    }
};
