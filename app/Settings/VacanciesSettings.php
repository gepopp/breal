<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class VacanciesSettings extends Settings
{
    // Vacancies/Career Page
    public string $vacancies_header_de = 'inspired';

    public string $vacancies_header_en = 'inspired';

    public string $vacancies_subheader_de = 'Werde Teil des be real Teams';

    public string $vacancies_subheader_en = 'Become Part of the be real Team';

    public string $vacancies_introtext_de = '<p>be real bietet ihnen nicht nur eine berufliche Herausforderung, sondern eine Gemeinschaft, die für Teamgeist und Zusammenarbeit steht. Die Arbeit in der Hausverwaltung ist ein vielseitiger Job, der aufregend und erfüllend ist. Jeder Tag ist anders und bringt viele neue Wege, sich kreativ auszuleben. Ein Job in der Immobilienverwaltung bedeutet Problemlöser, Kratzbürste und Kummerkasten in einem zu sein. Menschenkenntnis und die Leidenschaft für Kundennähe sind uns deshalb besonders wichtig. Klingt spannend? Entdecken Sie unsere Möglichkeiten, um in einem inspirierenden Umfeld zu wachsen und die Immobilienbranche aktiv mitzugestalten.</p><p><strong>Sie haben den Wunsch, sich initiativ zu bewerben? Schicken Sie bitte Ihren Lebenslauf und ein aussagekräftiges Bewerbungsschreiben an </strong><a href="mailto: office@bereal-immobilien.at"><strong>office@bereal-immobilien.at</strong></a></p>';

    public string $vacancies_introtext_en = '<p>be real offers you not only a professional challenge, but a community that stands for team spirit and collaboration. The work in property management is a versatile job that is exciting and fulfilling. Every day is different and brings many new ways to express yourself creatively. A job in property management means being a problem solver, a straight talker and a confidant all in one. People skills and a passion for customer proximity are therefore particularly important to us. Sounds exciting? Discover our opportunities to grow in an inspiring environment and actively help shape the real estate industry.</p><p><strong>Do you wish to apply on your own initiative? Please send your CV and a meaningful cover letter to </strong><a href="mailto: office@bereal-immobilien.at"><strong>office@bereal-immobilien.at</strong></a></p>';

    public string $cold_application_cta_text_de = 'Bewerben Sie sich gerne, wir suchen laufend nach engagierten, leistungsbereiten und leidenschaftlichen Mitarbeiter:innen! Schicken Sie gerne Ihren Lebenslauf an: wewantyou(at)bereal-immobilien.at';

    public string $cold_application_cta_text_en = 'Feel free to apply, we are constantly looking for committed, performance-oriented and passionate employees! Please send your CV to: wewantyou(at)bereal-immobilien.at';

    public static function group(): string
    {
        return 'translatable_vacancies';
    }
}
