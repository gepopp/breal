<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The privacy policy came from a German template: it cited the German TTDSG,
 * talked about a shopping cart and payment cookies, counted audience measurement
 * as a necessary cookie and named no supervisory authority. The affected
 * sentences are replaced in the stored texts; texts that do not contain them
 * (or were rewritten in the admin) are left alone, and running it twice changes nothing.
 */
return new class extends Migration
{
    private const AUTHORITY = 'Datenschutzbehörde, Barichgasse 40–42, 1030 Wien, E-Mail: dsb@dsb.gv.at, Website: www.dsb.gv.at';

    /**
     * @return array<string, string>
     */
    private function replacements(): array
    {
        return [
            'zusätzlich auf Grundlage von § 25 Abs. 1 TTDSG.' => 'zusätzlich auf Grundlage von § 165 Abs. 3 TKG 2021.',
            '(Art. 6 Abs. 1 lit. a DSGVO und § 25 Abs. 1 TTDSG)' => '(Art. 6 Abs. 1 lit. a DSGVO und § 165 Abs. 3 TKG 2021)',
            'z. B. die Warenkorbfunktion oder die Anzeige von Videos' => 'z. B. der Schutz der Formulare vor missbräuchlichen Anfragen oder die Speicherung Ihrer Cookie-Auswahl',
            '(z. B. für die Warenkorbfunktion) oder zur Optimierung der Website (z. B. Cookies zur Messung des Webpublikums) erforderlich sind' => '(z. B. für den Schutz der Formulare) erforderlich sind',
            '(z. B. Cookies zur Abwicklung von Zahlungsdienstleistungen)' => '(z. B. Cookies von Google Analytics, Google Ads oder Google Maps)',
            'steht Ihnen ein Beschwerderecht bei der zuständigen Aufsichtsbehörde zu.' => 'steht Ihnen ein Beschwerderecht bei der zuständigen Aufsichtsbehörde zu. In Österreich ist dies die Datenschutzbehörde.',
            'oder gerichtlicher Rechtsbehelfe.</p>' => 'oder gerichtlicher Rechtsbehelfe.</p><p>In Österreich ist die zuständige Aufsichtsbehörde die '.self::AUTHORITY.'.</p>',
            '<h2>3. Data collection on this website</h2>' => '<h3>Right to lodge a complaint with the supervisory authority</h3><p>If you believe that the processing of your personal data violates data protection law, you have the right to lodge a complaint with a supervisory authority. In Austria, the competent supervisory authority is the Datenschutzbehörde (Austrian Data Protection Authority), Barichgasse 40–42, 1030 Vienna, e-mail: dsb@dsb.gv.at, website: www.dsb.gv.at.</p><h2>3. Data collection on this website</h2>',
        ];
    }

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

                $updated = $html;

                foreach ($this->replacements() as $search => $replace) {
                    if (str_contains($updated, $search) && ! str_contains($updated, $replace)) {
                        $updated = str_replace($search, $replace, $updated);
                    }
                }

                if ($updated !== $html) {
                    DB::table('settings')->where('id', $row->id)->update([
                        'payload' => json_encode($updated, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Correction of legal references, nothing to restore.
    }
};
