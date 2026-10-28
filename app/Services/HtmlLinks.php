<?php

namespace App\Services;

/**
 * Repairs contact links and the company address in HTML that was typed into
 * the admin panel, so wrong stored values ("mail:", "mailto: office@...",
 * "tel:+43 1 535 36 19", "Franz Josefs Kai 65") never reach the page.
 */
class HtmlLinks
{
    public static function normalize(string $html): string
    {
        if (! str_contains($html, 'href=') && ! str_contains($html, 'Kai')) {
            return $html;
        }

        $html = preg_replace('/href=(["\'])mail:/i', 'href=$1mailto:', $html);
        $html = preg_replace('/href=(["\'])mailto:(?:%20|&nbsp;|\s)+/i', 'href=$1mailto:', $html);
        $html = preg_replace_callback(
            '/href=(["\'])tel:([^"\']*)\1/i',
            fn (array $match): string => 'href='.$match[1].CompanyInfo::telUri(rawurldecode($match[2])).$match[1],
            $html
        );
        $html = preg_replace('/Franz[ -]Josefs?[ -]Kai/u', 'Franz-Josefs-Kai', $html);

        return preg_replace('#Franz-Josefs-Kai 65(?!/Mezzanin)#u', 'Franz-Josefs-Kai 65/Mezzanin', $html);
    }
}
