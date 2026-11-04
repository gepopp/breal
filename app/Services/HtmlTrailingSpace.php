<?php

namespace App\Services;

/**
 * Removes empty trailing markup from admin texts: empty paragraphs (`<p><br></p>`)
 * and `<br>` / `&nbsp;` / whitespace at the end of the last paragraph. They add
 * uneven space between a text and the element that follows it.
 */
class HtmlTrailingSpace
{
    private const EMPTY = '(?:\s|&nbsp;|\x{00A0}|<br\s*\/?>)';

    public static function trim(string $html): string
    {
        do {
            $before = $html;

            $html = preg_replace('~<p>'.self::EMPTY.'*<\/p>\s*$~iu', '', $html) ?? $html;
            $html = preg_replace('~'.self::EMPTY.'+(<\/p>)\s*$~iu', '$1', $html) ?? $html;
            $html = preg_replace('~'.self::EMPTY.'+$~iu', '', $html) ?? $html;
        } while ($html !== $before);

        return $html;
    }
}
