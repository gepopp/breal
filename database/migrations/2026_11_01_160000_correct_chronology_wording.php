<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Editorial corrections in the chronology (timeline slider):
 * the missing space after "Beratung." (and the sentence fragment before it),
 * the first-person "mir"/"my" in the entry about Lukas Eybel (the rest of the
 * chronology is in the third person), the English translation of the company
 * name "Bontus Immobilienverwaltung" and invisible zero-width characters.
 * Only these exact phrases are replaced, so edited texts stay untouched.
 */
return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private const REPLACEMENTS = [
        'Ob Verkauf, Vermietung oder individuelle Beratung.Unser Maklerservice' => 'Ob Verkauf, Vermietung oder individuelle Beratung – unser Maklerservice',
        'Beratung.Unser' => 'Beratung. Unser',
        'war es mir wichtig' => 'war es ihm wichtig',
        'it was important to me to get' => 'it was important to him to get',
        'my current responsibilities' => 'his current responsibilities',
        'Bontus Property Management' => 'Bontus Immobilienverwaltung',
    ];

    public function up(): void
    {
        DB::table('timelines')->get(['id', 'title', 'description'])->each(function (object $row): void {
            $changes = [];

            foreach (['title', 'description'] as $column) {
                $value = json_decode((string) $row->{$column}, true);

                if (! is_array($value)) {
                    continue;
                }

                $corrected = array_map(fn (mixed $text): mixed => is_string($text) ? $this->corrected($text) : $text, $value);

                if ($corrected !== $value) {
                    $changes[$column] = json_encode($corrected, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }

            if ($changes !== []) {
                DB::table('timelines')->where('id', $row->id)->update($changes);
            }
        });
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }

    private function corrected(string $text): string
    {
        $text = preg_replace('/[\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', '', $text);
        $text = preg_replace('/\s+–\s+and\b/u', ' – and', $text);

        return trim(strtr($text, self::REPLACEMENTS));
    }
};
