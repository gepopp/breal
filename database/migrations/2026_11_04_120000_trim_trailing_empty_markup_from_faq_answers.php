<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Several FAQ answers end in empty paragraphs (`<p><br></p>`) or in `<br>` / `&nbsp;`
 * inside the last paragraph. That adds extra space between the answer and the
 * contact banner, and the distance differs from answer to answer. Only the end of
 * each answer is trimmed; the rest of the text is left untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('questions')->get(['id', 'answer'])->each(function (object $row): void {
            $answer = json_decode((string) $row->answer, true);

            if (! is_array($answer)) {
                return;
            }

            $changed = false;

            foreach ($answer as $locale => $html) {
                if (! is_string($html)) {
                    continue;
                }

                $trimmed = $this->trimTrailingEmptyMarkup($html);

                if ($trimmed !== $html) {
                    $answer[$locale] = $trimmed;
                    $changed = true;
                }
            }

            if ($changed) {
                DB::table('questions')->where('id', $row->id)->update([
                    'answer' => json_encode($answer, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
            }
        });
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }

    private function trimTrailingEmptyMarkup(string $html): string
    {
        $empty = '(?:\s|&nbsp;|\x{00A0}|<br\s*\/?>)';

        do {
            $before = $html;

            // Empty trailing paragraphs, e.g. <p><br></p>.
            $html = preg_replace('~<p>'.$empty.'*<\/p>\s*$~iu', '', $html) ?? $html;
            // Line breaks and spaces at the end of the last paragraph.
            $html = preg_replace('~'.$empty.'+(<\/p>)\s*$~iu', '$1', $html) ?? $html;
            // Loose line breaks and spaces at the very end.
            $html = preg_replace('~'.$empty.'+$~iu', '', $html) ?? $html;
        } while ($html !== $before);

        return $html;
    }
};
