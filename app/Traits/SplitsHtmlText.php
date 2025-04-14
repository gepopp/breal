<?php

namespace App\Traits;

use DOMDocument;
use DOMXPath;
use DOMElement;
use DragonCode\Support\Facades\Helpers\Str;

trait SplitsHtmlText
{
    public function prepareText(): string|array
    {
        $text = html_entity_decode($this->text);
        $plain_text = strip_tags($text);

        $count = Str::length($plain_text);

        if ($count > 250) {
            $split = $this->splitHtmlContentInHalf($text);
            return $split;
        }

        return $text;

    }


    /**
     * Teilt HTML-Inhalt in zwei möglichst gleiche Hälften unter Beibehaltung der Absatzstruktur
     * Mit spezieller Behandlung für den Fall eines einzelnen Absatzes
     *
     * @param string $htmlContent Der zu teilende HTML-Inhalt
     * @return array Ein Array mit den Schlüsseln 'firstHalf' und 'secondHalf'
     */
    function splitHtmlContentInHalf(string $htmlContent): array
    {
        // Erstelle DOM-Dokument
        $dom = new DOMDocument();
        // Unterdrücke Warnungen bei ungültigem HTML
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        // Hole alle Absätze und andere relevante Block-Elemente
        $xpath = new DOMXPath($dom);
        $blockElements = $xpath->query('//p|//div|//h1|//h2|//h3|//h4|//h5|//h6|//ul|//ol');

        // Wenn es keine Elemente gibt, leere Ausgabe zurückgeben
        if ($blockElements->length === 0) {
            return [
                'firstHalf' => '',
                'secondHalf' => ''
            ];
        }

        // Sonderfall: Nur ein Absatz/Element vorhanden
        if ($blockElements->length === 1) {
            return $this->splitSingleElement($blockElements->item(0), $dom);
        }

        // Normaler Fall: Mehrere Elemente vorhanden
        $totalLength = 0;
        foreach ($blockElements as $element) {
            $totalLength += strlen(trim($element->textContent));
        }

        $halfwayPoint = $totalLength / 2;
        $runningLength = 0;
        $splitIndex = 0;

        foreach ($blockElements as $index => $element) {
            $elementLength = strlen(trim($element->textContent));
            $runningLength += $elementLength;

            if ($runningLength >= $halfwayPoint) {
                $splitIndex = $index;
                break;
            }
        }

        $beforeSplitLength = $runningLength - strlen(trim($blockElements[$splitIndex]->textContent));
        $afterSplitLength = $runningLength;

        $halfwayDiffBefore = abs($halfwayPoint - $beforeSplitLength);
        $halfwayDiffAfter = abs($halfwayPoint - $afterSplitLength);

        $splitIndex = $halfwayDiffBefore < $halfwayDiffAfter ? $splitIndex : $splitIndex + 1;

        $firstHalfDom = new DOMDocument();
        $secondHalfDom = new DOMDocument();

        $firstHalfBody = $firstHalfDom->createElement('body');
        $secondHalfBody = $secondHalfDom->createElement('body');

        $firstHalfDom->appendChild($firstHalfBody);
        $secondHalfDom->appendChild($secondHalfBody);

        foreach ($blockElements as $index => $element) {
            $importedNode = null;

            if ($index < $splitIndex) {
                $importedNode = $firstHalfDom->importNode($element, true);
                $firstHalfBody->appendChild($importedNode);
            } else {
                $importedNode = $secondHalfDom->importNode($element, true);
                $secondHalfBody->appendChild($importedNode);
            }
        }

        $firstHalfHtml = '';
        $secondHalfHtml = '';

        foreach ($firstHalfBody->childNodes as $node) {
            $firstHalfHtml .= $firstHalfDom->saveHTML($node);
        }

        foreach ($secondHalfBody->childNodes as $node) {
            $secondHalfHtml .= $secondHalfDom->saveHTML($node);
        }

        return [
            'firstHalf' => $firstHalfHtml,
            'secondHalf' => $secondHalfHtml
        ];
    }

    /**
     * Hilfsfunktion zur Aufteilung eines einzelnen Elements
     *
     * @param DOMElement $element Das aufzuteilende Element
     * @param DOMDocument $originalDom Das ursprüngliche DOM-Dokument
     * @return array Ein Array mit den Schlüsseln 'firstHalf' und 'secondHalf'
     */
    function splitSingleElement(DOMElement $element, DOMDocument $originalDom): array
    {
        // Text des Elements extrahieren
        $text = $element->textContent;
        $textLength = strlen($text);

        // Bestimme den Teilungspunkt (bei 50% der Textlänge)
        $splitPoint = (int)($textLength / 2);

        // Finde einen geeigneten Teilungspunkt (z.B. nächstes Leerzeichen)
        // Suche vorwärts und rückwärts nach einem Leerzeichen in der Nähe des Mittelpunkts
        $forwardPos = strpos($text, ' ', $splitPoint);
        $backwardPos = strrpos(substr($text, 0, $splitPoint), ' ');

        // Ermittle, welcher Punkt näher am Mittelpunkt liegt
        if ($forwardPos !== false && $backwardPos !== false) {
            $splitPoint = ($forwardPos - $splitPoint < $splitPoint - $backwardPos) ? $forwardPos : $backwardPos;
        } elseif ($forwardPos !== false) {
            $splitPoint = $forwardPos;
        } elseif ($backwardPos !== false) {
            $splitPoint = $backwardPos;
        }
        // Wenn kein Leerzeichen gefunden wurde, bleibt splitPoint in der Mitte

        // Teile den Text und erstelle neue Elemente
        $firstHalfText = substr($text, 0, $splitPoint);
        $secondHalfText = substr($text, $splitPoint);

        // Erstelle zwei neue DOM-Dokumente für die Hälften
        $firstHalfDom = new DOMDocument();
        $secondHalfDom = new DOMDocument();

        // Kopiere das Element und setze den Inhalt
        $firstElement = $firstHalfDom->importNode($element, false); // Ohne Kinder importieren
        $secondElement = $secondHalfDom->importNode($element, false);

        $firstHalfDom->appendChild($firstElement);
        $secondHalfDom->appendChild($secondElement);

        // Füge Text hinzu
        $firstElement->textContent = $firstHalfText;
        $secondElement->textContent = $secondHalfText;

        // HTML zurückgeben
        return [
            'firstHalf' => $firstHalfDom->saveHTML($firstElement),
            'secondHalf' => $secondHalfDom->saveHTML($secondElement)
        ];
    }
}