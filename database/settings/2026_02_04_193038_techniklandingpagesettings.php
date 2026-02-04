<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('translatable_technik.hero_image', NULL);
        $this->migrator->add('translatable_technik.hero_image_alt_de', 'be real Intro Bild');
        $this->migrator->add('translatable_technik.hero_image_alt_en', 'be real Intro Image');
        $this->migrator->add('translatable_technik.hero_header_de', 'engineering');
        $this->migrator->add('translatable_technik.hero_header_en', 'engineering');
        $this->migrator->add('translatable_technik.hero_subheader_de', 'Die Technik für Hausverwaltung und Immobilienmanagement');
        $this->migrator->add('translatable_technik.hero_subheader_en', 'Engineering for Property Management and Real Estate Management');
        $this->migrator->add('translatable_technik.hero_introtext_de', '<p>Mit Fokus auf Machbarkeit, Effizienz und mit Verständnis für die Standards der Branche sorgen wir dafür, dass der Wert erhalten bleibt und Potenziale genutzt werden. Von einfachen Bauvorhaben bis zur Umsetzung komplexer Sanierungskonzepte begleiten wir Immobilien - nachhaltig, wirtschaftlich, verantwortungsvoll.</p>');
        $this->migrator->add('translatable_technik.hero_introtext_en', '<p>With a focus on feasibility, efficiency and understanding of industry standards, we ensure that value is preserved and potential is utilized. From simple construction projects to the implementation of complex renovation concepts, we accompany properties - sustainably, economically, responsibly.</p>');
        $this->migrator->add('translatable_technik.intro_layout', 'two_columns');
        $this->migrator->add('translatable_technik.hero_text_column_one_de', '<p>Unser Ziel ist es, den Wert jeder Immobilie zu bewahren, aber auch zu steigern, indem wir sie mit Sorgfalt und Hingabe pflegen. Wir streben jeden Tag danach, die Erwartungen unserer Kunden zu übertreffen. Wenn unsere Arbeit so nahtlos verläuft, dass niemand merkt, dass wir im Hintergrund agieren, dann wissen wir, dass wir unsere Aufgabe erfolgreich erfüllt haben. Wir setzen uns mit Begeisterung dafür ein, dass die uns anvertraute Immobilie nicht nur rundum betreut, sondern auch verwaltet und schlussendlich zeitgemäß gemanaged wird.</p>');
        $this->migrator->add('translatable_technik.hero_text_column_one_en', '<p>Our goal is to preserve, but also increase the value of every property by caring for it with diligence and dedication. We strive every day to exceed our customers\' expectations. When our work runs so seamlessly that no one notices we are acting in the background, we know we have successfully fulfilled our task. We are passionately committed to ensuring that the property entrusted to us is not only comprehensively cared for, but also managed and ultimately managed in a contemporary manner.</p>');
        $this->migrator->add('translatable_technik.hero_text_column_two_de', '<p>Sollte aber doch mal wo der Haken drin sein, dann sind wir zur Stelle und bereit jedes Problem zu lösen: von der störrischen Glühbirne über jegliche Schadensfälle bis hin zu komplexen Sanierungen - wir nehmen uns Ihrer Problemen an!</p><p>Es erfüllt uns mit Freude, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden!</p>');
        $this->migrator->add('translatable_technik.hero_text_column_two_en', '<p>But if there is a problem somewhere, we are there and ready to solve every problem: from the stubborn light bulb to any damage cases to complex renovations - we take care of your problems!</p><p>It fills us with joy to master challenges and find tailor-made solutions for you!</p>');
        $this->migrator->add('translatable_technik.text_de', '<p>Die Anforderungen an Hausverwaltungen wachsen – ebenso wie die Komplexität der Gebäude. Technisches Know-how wird zur Schlüsselkompetenz. Wir verbinden ingenieurtechnisches Verständnis mit operativer Erfahrung. So entstehen Lösungen, die funktionieren – für die Bausubstanz und die Menschen, die darin leben.&nbsp;</p>');
        $this->migrator->add('translatable_technik.text_en', '<p>The demands on property management are growing – as is the complexity of buildings. Technical know-how is becoming a key competence. We combine engineering understanding with operational experience. This creates solutions that work – for the building structure and the people who live in it.&nbsp;</p>');
        $this->migrator->add('translatable_technik.text_image', 0);
        $this->migrator->add('translatable_technik.text_image_alt_de', 'be real Intro Bild');
        $this->migrator->add('translatable_technik.text_image_alt_en', 'be real Intro Image');
        $this->migrator->add('translatable_technik.about_header_de', 'competent');
        $this->migrator->add('translatable_technik.about_header_en', 'competent');
        $this->migrator->add('translatable_technik.about_subheader_de', 'Partnerschaft für Erfolg');
        $this->migrator->add('translatable_technik.about_subheader_en', 'Partnership for Success');
        $this->migrator->add('translatable_technik.about_text_de', '<p>Bauen ist eine Aufgabe für Profis. Unser gesamtes Team besteht aus Experten, die die besonderen Herausforderungen von Immobilien kennen, wenn es um Sanierung und nachhaltige Erhaltung des Wertes von Gebäuden geht. Nur wenn beide Welten - Verwaltung und Technik - eng miteinander verzahnt sind und zusammenarbeiten, sind erfolgreiche Lösungen möglich.&nbsp;</p>');
        $this->migrator->add('translatable_technik.about_text_en', '<p>Building is a task for professionals. Our entire team consists of experts who know the special challenges of properties when it comes to renovation and sustainable preservation of the value of buildings. Successful solutions are only possible if both worlds - administration and technology - are closely interlinked and work together.&nbsp;</p>');
        $this->migrator->add('translatable_technik.about_image', 0);
        $this->migrator->add('translatable_technik.about_image_alt_de', 'Bontus Eybel Intro Bild');
        $this->migrator->add('translatable_technik.about_image_alt_en', 'Bontus Eybel Intro Image');
        $this->migrator->add('translatable_technik.about_video_embed_code', '');
    }
};
