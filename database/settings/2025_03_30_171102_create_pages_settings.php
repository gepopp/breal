<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('pages.contact_introtext', 'Am liebsten sprechen wir persönlich mit Ihnen über Ihre Anliegen. Wir freuen uns, von Ihnen zu hören und gemeinsam Großartiges zu schaffen.');
        $this->migrator->add('pages.contactpersons_introtext', 'Zusammenarbeit funktioniert am besten, wenn sie wissen, wer am anderen Ende der Leitung sitzt. Hier dürfen wir Ihnen die zuständigen MitarbeiterInnen vorstellen.');
        $this->migrator->add('pages.vacancies_introtext', 'be real bietet ihnen nicht nur eine berufliche Herausforderung, sondern
eine Gemeinschaft, die für Teamgeist und Zusammenarbeit steht. Die
Arbeit in der Hausverwaltung ist ein vielseitiger Job, der aufregend und
erfüllend ist. Jeder Tag ist anders und bringt viele neue Wege, sich
kreativ auszuleben. Ein Job in der Immobilienverwaltung bedeutet
Problemlöser, Kratzbürste und Kummerkasten in einem zu sein.
Menschenkenntnis und die Leidenschaft für Kundennähe sind uns
deshalb besonders wichtig.

Klingt spannend? Entdecken Sie unsere Möglichkeiten, um in einem
inspirierenden Umfeld zu wachsen und die Immobilienbranche aktiv
mitzugestalten.');
        $this->migrator->add('pages.cold_application_cta_text', 'Bewerben Sie sich gerne, wir suchen laufend nach engagierten, leistungsbereiten und leidenschaftlichen Mitarbeiter:innen! Schicken Sie gerne Ihren Lebenslauf an: wewantyou(at)bereal-immobilien.at');
    }
};
