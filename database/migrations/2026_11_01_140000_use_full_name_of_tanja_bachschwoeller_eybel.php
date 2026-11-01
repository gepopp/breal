<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corporate wording: the managing director is "Tanja Bachschwöller-Eybel",
 * but the Makler and homepage texts still said "Tanja Bachschwöller".
 * Only the exact old name is replaced, so a name already written in full and
 * unrelated text stay untouched. Imported property feed data is left alone.
 */
return new class extends Migration
{
    private const OLD_NAME = '/Tanja Bachschwöller(?!-Eybel)/u';

    private const NEW_NAME = 'Tanja Bachschwöller-Eybel';

    public function up(): void
    {
        DB::table('settings')
            ->where('payload', 'like', '%Bachschw%')
            ->get(['id', 'payload'])
            ->each(fn (object $row) => $this->update('settings', $row->id, 'payload', $row->payload));

        DB::table('timelines')
            ->where('description', 'like', '%Bachschw%')
            ->get(['id', 'description'])
            ->each(fn (object $row) => $this->update('timelines', $row->id, 'description', $row->description));
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }

    private function update(string $table, int $id, string $column, ?string $json): void
    {
        $value = json_decode((string) $json, true);

        if ($value === null) {
            return;
        }

        $corrected = $this->replace($value);

        if ($corrected !== $value) {
            DB::table($table)->where('id', $id)->update([
                $column => json_encode($corrected, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
        }
    }

    private function replace(mixed $value): mixed
    {
        if (is_string($value)) {
            return preg_replace(self::OLD_NAME, self::NEW_NAME, $value);
        }

        if (is_array($value)) {
            return array_map(fn (mixed $item): mixed => $this->replace($item), $value);
        }

        return $value;
    }
};
