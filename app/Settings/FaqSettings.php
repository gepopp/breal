<?php

namespace App\Settings;

class FaqSettings extends BaseSettings
{
    // FAQ Page
    public string $faq_header_de = 'informed';

    public string $faq_header_en = 'informed';

    public string $faq_subheader_de = 'Antworten auf häufig gestellte Fragen';

    public string $faq_subheader_en = 'Answers to Frequently Asked Questions';

    public string $faq_introtext_de = '<p>Immobilienverwaltung ist Vertrauenssache. Deshalb haben wir für Sie die wichtigsten Fragen zusammengestellt – klar, verständlich und mit ganz viel Herzblut beantwortet. Egal, ob es um Eigentum, Mietrecht oder nachhaltige Modernisierung geht: Wir stehen an Ihrer Seite und sorgen dafür, dass Sie sich entspannt zurücklehnen können. Finden Sie hier Antworten – und bei uns den richtigen Partner für Ihre Immobilie.&nbsp;</p>';

    public string $faq_introtext_en = '<p>Property management is a matter of trust. That\'s why we have compiled the most important questions for you - answered clearly, understandably and with a lot of passion. Whether it\'s about ownership, tenancy law or sustainable modernization: We stand by your side and ensure that you can sit back and relax. Find answers here - and the right partner for your property with us.&nbsp;</p>';

    public static function group(): string
    {
        return 'translatable_faq';
    }
}
