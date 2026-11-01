<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Audit R34 (proofreading): texts copied into the admin carried zero-width
 * characters (zero-width space, joiners, word joiner, byte order mark). They
 * are invisible but break words for screen readers and search. Only these
 * characters are removed, in the stored JSON as well as in plain text.
 */
return new class extends Migration
{
    private const INVISIBLE = '/\\\\u(?:200[bcdBCD]|2060|[fF]{3}[eE])|[\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u';

    /**
     * @var array<string, list<string>>
     */
    private const COLUMNS = [
        'settings' => ['payload'],
        'questions' => ['question', 'answer'],
    ];

    public function up(): void
    {
        foreach (self::COLUMNS as $table => $columns) {
            DB::table($table)->get()->each(function (object $row) use ($table, $columns): void {
                $changes = [];

                foreach ($columns as $column) {
                    $value = $row->{$column};

                    if (is_string($value) && preg_match(self::INVISIBLE, $value)) {
                        $changes[$column] = preg_replace(self::INVISIBLE, '', $value);
                    }
                }

                if ($changes !== []) {
                    DB::table($table)->where('id', $row->id)->update($changes);
                }
            });
        }
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }
};
