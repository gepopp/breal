<?php

namespace App\Settings;

class LeistungenSettings extends BaseSettings
{
    // Hausverwaltung Leistungen
    public string $hausverwaltung_leistungen_header_de = 'competent';

    public string $hausverwaltung_leistungen_header_en = 'competent';

    public string $hausverwaltung_leistungen_subheader_de = 'Unsere Leistungen';

    public string $hausverwaltung_leistungen_subheader_en = 'Our Services';

    public string $hausverwaltung_leistungen_introtext_de = '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>';

    public string $hausverwaltung_leistungen_introtext_en = '<p>We are passionate about property management - from condominium management and tenancy law to construction management and general consulting - we make sure you feel well looked after all around. Partnership-based cooperation, individual solutions and comprehensive advice are our top priorities.</p>';

    // Immobilien/Makler Leistungen
    public string $immobilien_leistungen_header_de = 'competent';

    public string $immobilien_leistungen_header_en = 'competent';

    public string $immobilien_leistungen_subheader_de = 'Unsere Leistungen';

    public string $immobilien_leistungen_subheader_en = 'Our Services';

    public string $immobilien_leistungen_introtext_de = '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>';

    public string $immobilien_leistungen_introtext_en = '<p>We are passionate about property management - from condominium management and tenancy law to construction management and general consulting - we make sure you feel well looked after all around. Partnership-based cooperation, individual solutions and comprehensive advice are our top priorities.</p>';

    // Technik Leistungen
    public string $technik_leistungen_header_de = 'competent';

    public string $technik_leistungen_header_en = 'competent';

    public string $technik_leistungen_subheader_de = 'Unsere Leistungen';

    public string $technik_leistungen_subheader_en = 'Our Services';

    public string $technik_leistungen_introtext_de = '<p>Technik ist nicht Selbstzweck – sie ist Grundlage für Substanz, Sicherheit und Zukunftsfähigkeit.<br>Unsere Leistungen im Bereich Bautechnik decken den gesamten Lebenszyklus einer Immobilie ab: von der strukturierten Ersterhebung über regelmäßige Objektbegehungen bis zur Entwicklung und Umsetzung komplexer Sanierungskonzepte. Wir denken integrativ – technische Präzision, rechtliche Sicherheit und wirtschaftliche Machbarkeit gehören für uns untrennbar zusammen.<br>&nbsp;Was das konkret bedeutet, sehen Sie hier im Überblick.&nbsp;</p>';

    public string $technik_leistungen_introtext_en = '<p>Technology is not an end in itself - it is the basis for substance, safety and future viability.<br>Our services in the field of construction technology cover the entire life cycle of a property: from structured initial surveys through regular property inspections to the development and implementation of complex renovation concepts. We think integratively - technical precision, legal certainty and economic feasibility are inseparable for us.<br>&nbsp;You can see what this means in concrete terms here at a glance.&nbsp;</p>';

    public static function group(): string
    {
        return 'translatable_leistungen';
    }
}
