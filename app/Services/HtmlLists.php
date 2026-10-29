<?php

namespace App\Services;

/**
 * Turns the hand-typed "• item<br>• item" paragraphs and bold-only
 * "Heading:" paragraphs of admin texts into real lists and headings.
 */
class HtmlLists
{
    public static function normalize(string $html): string
    {
        if (! str_contains($html, '•') && ! str_contains($html, '<strong>')) {
            return $html;
        }

        $html = preg_replace_callback(
            '#<p>\s*•(.*?)</p>#su',
            function (array $match): string {
                $items = array_filter(
                    array_map(
                        fn (string $item): string => trim(preg_replace('/^(?:\s|&nbsp;)+|(?:\s|&nbsp;|\.)+$/u', '', $item)),
                        preg_split('#<br\s*/?>\s*•#u', $match[1])
                    ),
                    fn (string $item): bool => $item !== ''
                );

                return '<ul>'.implode('', array_map(fn (string $item): string => '<li>'.$item.'</li>', $items)).'</ul>';
            },
            $html
        );

        return preg_replace('#<p>\s*<strong>([^<]+:)\s*</strong>\s*</p>#u', '<h3><strong>$1</strong></h3>', $html);
    }
}
