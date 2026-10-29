<?php

namespace App\Services;

/**
 * Turns the hand-typed "• item<br>• item" or one-paragraph-per-"· item" texts
 * and bold-only "Heading:" paragraphs of admin texts into real lists and
 * headings.
 */
class HtmlLists
{
    private const BULLET = '[•·]';

    private const SPACE = '(?:\s|&nbsp;)';

    public static function normalize(string $html): string
    {
        if (! preg_match('/[•·]|<strong>/u', $html)) {
            return $html;
        }

        $html = preg_replace_callback(
            '#(?:<p>'.self::SPACE.'*'.self::BULLET.'.*?</p>'.self::SPACE.'*)+#su',
            function (array $run): string {
                preg_match_all('#<p>'.self::SPACE.'*'.self::BULLET.'(.*?)</p>#su', $run[0], $paragraphs);

                $items = [];

                foreach ($paragraphs[1] as $paragraph) {
                    foreach (preg_split('#<br\s*/?>'.self::SPACE.'*'.self::BULLET.'#u', $paragraph) as $item) {
                        $item = trim(preg_replace('/^'.self::SPACE.'+|'.self::SPACE.'+$|\.$/u', '', $item));

                        if ($item !== '') {
                            $items[] = '<li>'.$item.'</li>';
                        }
                    }
                }

                return '<ul>'.implode('', $items).'</ul>';
            },
            $html
        );

        $html = preg_replace('#<p>'.self::SPACE.'*</p>#u', '', $html);

        return preg_replace('#<p>\s*<strong>([^<]+:)\s*</strong>\s*</p>#u', '<h3><strong>$1</strong></h3>', $html);
    }
}
