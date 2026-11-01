<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Audit A08: the accessibility statement claimed full WCAG conformity and
 * referred to the federal WZG. It is replaced by a voluntary statement that
 * names the known gaps. Only a text that still contains the old claim is
 * replaced, so a statement edited in the admin in the meantime stays as it is.
 */
return new class extends Migration
{
    private const TEXTS = [
        'accessability_text_de' => ['vollständig vereinbar', '<p>be real Immobilienmanagement GmbH bemüht sich, die Website https://bereal-immobilien.at für möglichst alle Menschen nutzbar zu machen. Maßstab sind die „Richtlinien für barrierefreie Webinhalte“ (WCAG) 2.1, Konformitätsstufe AA.</p><p><strong>Stand der Vereinbarkeit</strong></p><p>Diese Website ist mit den Anforderungen der WCAG 2.1 Stufe AA teilweise vereinbar. Eine automatisierte Prüfung von 18 Seiten (Tastaturbedienung, Fokusdarstellung, Kontraste, Überschriften, Sprachangaben, Textalternativen, Darstellung bei 320 Pixel Breite) ergab keine offenen Mängel. Die folgenden Punkte sind noch nicht vollständig umgesetzt oder noch nicht geprüft:</p><ul><li>Einzelne Links und Schaltflächen sind kleiner als 24 × 24 Pixel (Anforderung der WCAG 2.2, nicht Teil von WCAG 2.1). Wir prüfen eine Vergrößerung.</li><li>Videos von Vimeo haben nicht in jedem Fall Untertitel.</li><li>Dokumente zum Download (PDF-Dateien, etwa Formulare) sind nicht durchgehend barrierefrei aufbereitet.</li><li>Objektbeschreibungen, Lagebeschreibungen und Objekttitel kommen aus einer externen Quelle und sind ausschließlich auf Deutsch verfügbar. Auf den englischen Seiten sind sie als deutschsprachig gekennzeichnet.</li><li>Eine manuelle Prüfung mit Screenreadern sowie mit vergrößerter Schrift und geändertem Textabstand steht noch aus.</li></ul><p><strong>Erstellung dieser Erklärung</strong></p><p>Diese Erklärung wurde am 1.11.2026 auf Grundlage einer Selbstbewertung erstellt. Sie wird bei wesentlichen Änderungen der Website überprüft und aktualisiert.</p><p><strong>Feedback und Kontakt</strong></p><p>Wenn Sie auf Barrieren stoßen oder Inhalte in anderer Form benötigen, schreiben Sie uns bitte an <a href="mailto:office@bereal-immobilien.at">office@bereal-immobilien.at</a>. Wir antworten so rasch wie möglich und stellen die gewünschten Inhalte nach Möglichkeit in einer zugänglichen Form bereit.</p>'],
        'accessability_text_en' => ['fully compliant', '<p>be real Immobilienmanagement GmbH aims to make the website https://bereal-immobilien.at usable for as many people as possible. We use the Web Content Accessibility Guidelines (WCAG) 2.1, conformance level AA, as our benchmark.</p><p><strong>Status of compliance</strong></p><p>This website partially conforms to WCAG 2.1 level AA. An automated check of 18 pages (keyboard operation, focus visibility, contrast, headings, language attributes, text alternatives, display at 320 pixels width) found no open defects. The following points are not yet fully implemented or not yet tested:</p><ul><li>Some links and buttons are smaller than 24 × 24 pixels (a WCAG 2.2 requirement, not part of WCAG 2.1). We are looking at making them larger.</li><li>Videos hosted on Vimeo do not always have captions.</li><li>Downloadable documents (PDF files such as forms) are not all prepared for accessibility.</li><li>Property titles, location and property descriptions come from an external source and are available in German only. On the English pages they are marked as German-language content.</li><li>A manual review with screen readers, and with enlarged text and changed text spacing, is still pending.</li></ul><p><strong>How this statement was prepared</strong></p><p>This statement was prepared on 1 November 2026 based on a self-assessment. It will be reviewed and updated whenever the website changes significantly.</p><p><strong>Feedback and contact</strong></p><p>If you run into barriers or need content in a different format, please write to <a href="mailto:office@bereal-immobilien.at">office@bereal-immobilien.at</a>. We will reply as soon as possible and, where feasible, provide the content in an accessible form.</p>'],
    ];

    public function up(): void
    {
        foreach (self::TEXTS as $name => [$oldClaim, $text]) {
            DB::table('settings')
                ->where('group', 'translatable_legal')
                ->where('name', $name)
                ->where('payload', 'like', '%'.$oldClaim.'%')
                ->update(['payload' => json_encode($text, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
        }
    }

    public function down(): void
    {
        // Text replacement, nothing to restore.
    }
};
