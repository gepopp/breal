<?php

use App\Models\Competence;
use App\Models\Contactperson;
use App\Models\JobVacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corrections found when comparing the German and English texts:
 * the English description of "Gewerbeimmobilienverwaltung" held the text of "Fördermanagement", the German search
 * result text of "Energieausweis" was missing, two job titles were placeholders ("ready", "accountant"), two
 * English job titles of team members did not match the German ones, and the subheader of the three services pages
 * read "Our Service". A value is only replaced while it still has the faulty wording, so texts changed in the admin
 * stay untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->correctCommercialPropertyDescription();
        $this->fillEnergyCertificateMetaDescription();
        $this->correctTeamPositions();
        $this->replaceJobTitlePlaceholders();
        $this->correctServicesSubheader();
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }

    private function correctCommercialPropertyDescription(): void
    {
        $competence = Competence::all()->first(fn (Competence $competence): bool => $competence->getTranslation('name', 'de', false) === 'Gewerbeimmobilienverwaltung');

        if ($competence && str_starts_with((string) $competence->getTranslation('description', 'en', false), 'The public sector offers numerous funding programs')) {
            $competence->setTranslation('description', 'en', 'Genuine passion brings know-how too! From legally sound contract drafting to coordinating maintenance, our all-round property management keeps your commercial property running smoothly and ensures it increases in value.');
            $competence->saveQuietly();
        }
    }

    private function fillEnergyCertificateMetaDescription(): void
    {
        $competence = Competence::all()->first(fn (Competence $competence): bool => $competence->getTranslation('name', 'de', false) === 'Energieausweis');

        if ($competence && blank($competence->getTranslation('meta_description', 'de', false))) {
            $competence->setTranslation('meta_description', 'de', 'Energieausweise von be real Technik: für Neubauten, Bestandsgebäude und Sanierungen, mit Beratung zur Verbesserung der Energieeffizienz.');
            $competence->saveQuietly();
        }
    }

    private function correctTeamPositions(): void
    {
        $corrections = [
            ['Talin Shikouian', 'Accounts Payable Team Manager', 'Accounts Payable Assistant'],
            ['Leander Krenn', 'Reception Assistant', 'Apprentice'],
        ];

        foreach ($corrections as [$name, $faulty, $corrected]) {
            Contactperson::withTrashed()->where('name', $name)->get()->each(function (Contactperson $person) use ($faulty, $corrected): void {
                if ($person->getTranslation('position', 'en', false) === $faulty) {
                    $person->setTranslation('position', 'en', $corrected);
                    $person->saveQuietly();
                }
            });
        }
    }

    private function replaceJobTitlePlaceholders(): void
    {
        $titles = [
            'ready' => [
                'Junior-Verwalter:in/Sachbearbeiter:in Immobilienverwaltung (m/w/d)',
                'Junior-Verwalter:in',
                'Junior Property Manager',
            ],
            'accountant' => [
                'Teamleitung Stammdaten Objektbuchhaltung (m/w/d)',
                'Teamleitung Stammdaten Objektbuchhaltung',
                'Team Leader Master Data Property Accounting',
            ],
        ];

        foreach ($titles as $placeholder => [$vacancyTitle, $de, $en]) {
            JobVacancy::all()
                ->filter(fn (JobVacancy $vacancy): bool => $vacancy->getTranslation('title', 'de', false) === $vacancyTitle
                    && $vacancy->getTranslation('job_title', 'de', false) === $placeholder)
                ->each(function (JobVacancy $vacancy) use ($placeholder, $de, $en): void {
                    $vacancy->setTranslation('job_title', 'de', $de);

                    if ($vacancy->getTranslation('job_title', 'en', false) === $placeholder) {
                        $vacancy->setTranslation('job_title', 'en', $en);
                    }

                    $vacancy->saveQuietly();
                });
        }
    }

    private function correctServicesSubheader(): void
    {
        foreach (['hausverwaltung', 'immobilien', 'technik'] as $company) {
            $row = DB::table('settings')
                ->where('group', 'translatable_leistungen')
                ->where('name', $company.'_leistungen_subheader_en')
                ->first();

            if ($row && json_decode($row->payload) === 'Our Service') {
                DB::table('settings')->where('id', $row->id)->update(['payload' => json_encode('Our Services')]);
            }
        }
    }
};
