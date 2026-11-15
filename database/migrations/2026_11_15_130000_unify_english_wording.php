<?php

use App\Models\Contactperson;
use App\Models\Timeline;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Makes the English wording consistent: the English job titles of team members ("Sachbearbeiter" was Advisor,
 * Clerk or Administrative Assistant depending on the person), the English title of the chronology entry about
 * the founding of Bontus Immobilienverwaltung, and the wording of the contact form consent, which differed
 * between the form and the page settings. A value is only replaced while it still has the old wording, so texts
 * edited in the admin stay untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->unifyTeamPositions();
        $this->completeChronologyTitle();
        $this->unifyContactFormConsent();
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }

    private function unifyTeamPositions(): void
    {
        $positions = [
            ['Sachbearbeiter', 'Advisor', 'Clerk'],
            ['Sachbearbeiterin', 'Advisor', 'Clerk'],
            ['Sachbearbeiterin Stammdaten', 'Master Data Advisor', 'Master Data Clerk'],
            ['Sachbearbeiterin Betrieb & Personal', 'Administrative Assistant for Operations & Personnel', 'Operations & HR Clerk'],
            ['Sachbearbeiterin Steuer & Beiblätter', 'Tax & Supplementary Documents Clerk', 'Tax & Supplementary Sheets Clerk'],
            ['Leitung Objektbuchhaltung', 'Head of property accounting', 'Head of Property Accounting'],
        ];

        Contactperson::withTrashed()->get()->each(function (Contactperson $person) use ($positions): void {
            foreach ($positions as [$german, $faulty, $corrected]) {
                if ($person->getTranslation('position', 'de', false) === $german && $person->getTranslation('position', 'en', false) === $faulty) {
                    $person->setTranslation('position', 'en', $corrected);
                    $person->saveQuietly();
                }
            }
        });
    }

    private function completeChronologyTitle(): void
    {
        Timeline::all()
            ->filter(fn (Timeline $entry): bool => $entry->getTranslation('title', 'de', false) === 'Neuausrichtung mit Tradition: Die Gründung der Bontus Immobilienverwaltung'
                && $entry->getTranslation('title', 'en', false) === 'Bontus Immobilienverwaltung established')
            ->each(function (Timeline $entry): void {
                $entry->setTranslation('title', 'en', 'Renewal with tradition: the founding of Bontus Immobilienverwaltung');
                $entry->saveQuietly();
            });
    }

    private function unifyContactFormConsent(): void
    {
        $row = DB::table('settings')->where('group', 'translatable_contact')->where('name', 'contactform_terms_en')->first();

        if ($row && json_decode($row->payload) === 'I agree to the processing and storage of my data, as well as to being contacted by email or telephone in the course of enquiry processing.') {
            DB::table('settings')->where('id', $row->id)->update([
                'payload' => json_encode('I agree to the processing and storage of my data, as well as to being contacted via email or phone in the course of processing my request.'),
            ]);
        }
    }
};
