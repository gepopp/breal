<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corrections to texts that already live in the database (briefing: WP9).
 * Every change only applies to the exact old value, so edits made in the
 * admin in the meantime are never overwritten and a fresh database is untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->replaceAltText('%makler', 'cta_image_alt_de', 'Bontus Eybel Intro Bild', 'be real Makler: Beratung zu Kauf, Verkauf und Vermietung');
        $this->replaceAltText('%makler', 'cta_image_alt_en', 'Bontus Eybel Intro Image', 'be real Makler: advice on buying, selling and letting');
        $this->replaceAltText('%technik', 'about_image_alt_de', 'Bontus Eybel Intro Bild', 'be real Technik: Baumanagement und technische Gebäudeverwaltung');
        $this->replaceAltText('%technik', 'about_image_alt_en', 'Bontus Eybel Intro Image', 'be real Technik: construction management and technical building management');
        $this->replaceAltText('%hausverwaltung%', 'about_image_alt_de', 'animation', 'Vorschaubild zum be real Video');
        $this->replaceAltText('%hausverwaltung%', 'about_image_alt_en', 'animation', 'Preview image of the be real video');

        DB::table('settings')
            ->where('name', 'like', 'contactpersons_introtext%')
            ->where('payload', 'like', '%MitarbeiterInnen%')
            ->get()
            ->each(fn (object $row) => DB::table('settings')->where('id', $row->id)->update([
                'payload' => str_replace('MitarbeiterInnen', 'Mitarbeiter:innen', $row->payload),
            ]));

        $this->replaceQuestion(
            'Wie ist der aktuelle Stand bei der Diskussion um Wertsicherungsklauseln in Mietverträgen',
            'Wie ist der aktuelle Stand bei der Diskussion um Wertsicherungsklauseln in Mietverträgen?',
        );
        $this->replaceQuestion('So einfach wechseln Sie Ihren Stromanbieter in Österreich', 'Wie wechsle ich meinen Stromanbieter in Österreich?');
        $this->replaceQuestion(
            'Kurzzeitvermietung in der EU: Was die neuen Regeln für Sie jetzt bedeuten',
            'Was bedeuten die neuen EU-Regeln zur Kurzzeitvermietung für mich?',
            ['ab Mai 2026 European CommissionRat der Europäischen Union.' => 'ab Mai 2026 (Quelle: Rat der Europäischen Union).'],
        );

        DB::table('references')->where('slug', 'triester-strasse')->get()->each(function (object $row): void {
            $title = json_decode($row->title, true);

            foreach (['de', 'en'] as $locale) {
                if (($title[$locale] ?? null) === 'Triester Strasse') {
                    $title[$locale] = 'Triester Straße';
                }
            }

            DB::table('references')->where('id', $row->id)->update(['title' => json_encode($title)]);
        });
    }

    public function down(): void
    {
        // Text corrections, nothing to restore.
    }

    private function replaceAltText(string $groupLike, string $name, string $old, string $new): void
    {
        DB::table('settings')
            ->where('group', 'like', $groupLike)
            ->where('name', $name)
            ->get()
            ->filter(fn (object $row): bool => json_decode($row->payload) === $old)
            ->each(fn (object $row) => DB::table('settings')->where('id', $row->id)->update(['payload' => json_encode($new)]));
    }

    /**
     * @param  array<string, string>  $answerReplacements
     */
    private function replaceQuestion(string $old, string $new, array $answerReplacements = []): void
    {
        DB::table('questions')->get()->each(function (object $row) use ($old, $new, $answerReplacements): void {
            $question = json_decode($row->question, true);

            if (($question['de'] ?? null) !== $old) {
                return;
            }

            $question['de'] = $new;
            $answer = json_decode($row->answer, true);
            $answer['de'] = strtr($answer['de'] ?? '', $answerReplacements);

            DB::table('questions')->where('id', $row->id)->update([
                'question' => json_encode($question, JSON_UNESCAPED_UNICODE),
                'answer' => json_encode($answer, JSON_UNESCAPED_UNICODE),
            ]);
        });
    }
};
