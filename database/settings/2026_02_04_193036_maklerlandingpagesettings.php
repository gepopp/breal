<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('translatable_makler.hero_images', NULL);
        $this->migrator->add('translatable_makler.intro_title_de', 'realtor');
        $this->migrator->add('translatable_makler.intro_title_en', 'realtor');
        $this->migrator->add('translatable_makler.intro_subtitle_de', 'Keine Immobilie gleicht der anderen und jede hat ihre eigene Geschichte.');
        $this->migrator->add('translatable_makler.intro_subtitle_en', 'No property is like any other and each has its own story.');
        $this->migrator->add('translatable_makler.intro_description_de', '<p>Bei be real Makler dreht sich alles um die professionelle Vermarktung und Vermittlung von Immobilien – ob Wohnen, Gewerbe oder Investment, zur Miete oder im Eigentum. Wir begleiten Sie bei der Vermarktung und Vermittlung von Mietwohnungen, Eigentumswohnungen, Geschäftslokalen oder Büros, Zinshäusern und Anlageobjekten – vom Erstbezug über Neubauprojekte bis hin zu Bestandsimmobilien. Gemeinsam mit Ihnen entwickeln wir eine individuell abgestimmte Strategie, die Ihre Immobilie optimal am Markt positioniert und gezielt die passende Zielgruppe anspricht. Dabei setzen wir auf fundierte Marktkenntnis, persönliche Beratung und einen klaren Fokus auf Vermittlungserfolg.</p>');
        $this->migrator->add('translatable_makler.intro_description_en', '<p>At be real Makler, everything revolves around the professional marketing and brokering of properties – whether residential, commercial or investment, for rent or ownership. We accompany you in the marketing and brokering of rental apartments, condominiums, business premises or offices, apartment buildings and investment properties – from first occupancy through new construction projects to existing properties. Together with you, we develop an individually tailored strategy that optimally positions your property on the market and specifically addresses the right target group. We rely on sound market knowledge, personal advice and a clear focus on brokerage success.</p>');
        $this->migrator->add('translatable_makler.cta_header_de', 'with us');
        $this->migrator->add('translatable_makler.cta_header_en', 'with us');
        $this->migrator->add('translatable_makler.cta_subheader_de', 'Wir vermitteln auch Ihre Immobilien');
        $this->migrator->add('translatable_makler.cta_subheader_en', 'We also broker your properties');
        $this->migrator->add('translatable_makler.cta_text_de', '<p>Für Ihre Vermittlungsanfragen stehen wir Ihnen jederzeit gerne persönlich zur Verfügung. Sie erreichen uns per E-Mail unter office@bereal-makler.at oder telefonisch unter +43 1 535 36 19 oder +43 676 54 26 774. Wir freuen uns darauf, Sie individuell zu beraten und gemeinsam die passende Lösung für Ihr Anliegen zu finden.</p>');
        $this->migrator->add('translatable_makler.cta_text_en', '<p>We are always personally available for your brokerage inquiries. You can reach us by email at office@bereal-makler.at or by phone at +43 1 535 36 19 or +43 676 54 26 774. We look forward to advising you individually and finding the right solution for your request together.</p>');
        $this->migrator->add('translatable_makler.cta_image', 0);
        $this->migrator->add('translatable_makler.cta_image_alt_de', 'Bontus Eybel Intro Bild');
        $this->migrator->add('translatable_makler.cta_image_alt_en', 'Bontus Eybel Intro Image');
        $this->migrator->add('translatable_makler.cta_video_embed_code', '');
        $this->migrator->add('translatable_makler.about_heading_de', 'Geschäftsführerin und Eigentümerin Tanja Bachschwöller, BA');
        $this->migrator->add('translatable_makler.about_heading_en', 'Managing Director and Owner Tanja Bachschwöller, BA');
        $this->migrator->add('translatable_makler.about_text_de', '<p>Im Frühjahr 2021 gründete Tanja Bachschwöller mit ihrem Lebensgefährten Lukas Eybel die Immobilienvermittlung be real Makler. Jahrelange Berufserfahrung, Aneignung von fundiertem Wissen, sowie das facheinschlägige Studium „Real Estate Management" an einer renommierten Universität in Österreich spiegeln sich in der maßgeschneiderten Beratung und dem charmanten Umgang mit Kunden wider. Aufgrund der hervorragenden Vernetzung in der Branche sowie der Zusammenarbeit mit mehreren Kooperationspartnern bietet sie jedem Immobiliensuchenden eine einwandfreie Abwicklung bei der Anmietung oder beim Kauf einer Immobilie.</p>');
        $this->migrator->add('translatable_makler.about_text_en', '<p>In spring 2021, Tanja Bachschwöller founded the real estate brokerage be real Makler together with her partner Lukas Eybel. Years of professional experience, acquisition of sound knowledge, as well as the relevant study "Real Estate Management" at a renowned university in Austria are reflected in the tailor-made advice and charming interaction with customers. Due to the excellent networking in the industry as well as the cooperation with several cooperation partners, she offers every property seeker a flawless transaction when renting or buying a property.</p>');
        $this->migrator->add('translatable_makler.about_image', 0);
        $this->migrator->add('translatable_makler.about_image_alt_de', 'Geschäftsführerin und Eigentümerin Tanja Bachschwöller, BA');
        $this->migrator->add('translatable_makler.about_image_alt_en', 'Managing Director and Owner Tanja Bachschwöller, BA');
    }
};
