<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The privacy policy still described services the website no longer uses:
 * Borlabs Cookie, Google Forms, Google Web Fonts and the old Google Maps text
 * (the actual services are listed in the overview section of the page).
 * The sections are cut out of the stored texts by their headings; texts that
 * do not contain them (or were rewritten in the admin) are left alone.
 */
return new class extends Migration
{
    /**
     * Heading text => heading tag. A section runs up to the next heading of the same or a higher level.
     *
     * @var array<string, string>
     */
    private const SECTIONS = [
        'Einwilligung mit Borlabs Cookie' => 'h3',
        'Google Forms' => 'h3',
        '4. Plugins und Tools' => 'h2',
        '4. Plugins and Tools' => 'h2',
    ];

    public function up(): void
    {
        DB::table('settings')
            ->whereIn('name', ['dpgr_text', 'dpgr_text_de', 'dpgr_text_en'])
            ->get(['id', 'payload'])
            ->each(function (object $row): void {
                $html = json_decode((string) $row->payload);

                if (! is_string($html)) {
                    return;
                }

                $cleaned = $this->withoutSections($html);

                if ($cleaned !== $html) {
                    DB::table('settings')->where('id', $row->id)->update([
                        'payload' => json_encode($cleaned, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Removal of outdated text, nothing to restore.
    }

    private function withoutSections(string $html): string
    {
        foreach (self::SECTIONS as $title => $tag) {
            $html = $this->withoutSection($html, $title, $tag);
        }

        return $html;
    }

    private function withoutSection(string $html, string $title, string $tag): string
    {
        $headings = '/<'.$tag.'\b[^>]*>(.*?)<\/'.$tag.'>/su';

        if (! preg_match_all($headings, $html, $matches, PREG_OFFSET_CAPTURE)) {
            return $html;
        }

        foreach ($matches[1] as $index => [$text]) {
            if (trim(html_entity_decode(strip_tags($text))) !== $title) {
                continue;
            }

            $start = $matches[0][$index][1];
            $stopAt = $tag === 'h2' ? '/<h2\b/u' : '/<h[23]\b/u';
            $from = $start + strlen($matches[0][$index][0]);

            $end = preg_match($stopAt, $html, $next, PREG_OFFSET_CAPTURE, $from) ? $next[0][1] : strlen($html);

            return substr($html, 0, $start).substr($html, $end);
        }

        return $html;
    }
};
