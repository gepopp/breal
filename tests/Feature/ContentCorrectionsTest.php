<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Reference;
use Database\Factories\QuestionFactory;
use Database\Factories\ReferenceFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ContentCorrectionsTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_10_28_230557_correct_existing_content_texts.php'))->up();
    }

    private function setting(string $group, string $name, string $value): int
    {
        DB::table('settings')->updateOrInsert(['group' => $group, 'name' => $name], ['locked' => false, 'payload' => json_encode($value)]);

        return (int) DB::table('settings')->where('group', $group)->where('name', $name)->value('id');
    }

    private function payload(int $id): string
    {
        return json_decode(DB::table('settings')->where('id', $id)->value('payload'));
    }

    public function test_the_old_brand_and_placeholder_alt_texts_are_replaced(): void
    {
        $makler = $this->setting('translatable_makler', 'cta_image_alt_de', 'Bontus Eybel Intro Bild');
        $technik = $this->setting('translatable_technik', 'about_image_alt_en', 'Bontus Eybel Intro Image');
        $video = $this->setting('translatable_hausverwaltung_landingpage', 'about_image_alt_de', 'animation');

        $this->runMigration();

        $this->assertStringStartsWith('be real Makler', $this->payload($makler));
        $this->assertStringStartsWith('be real Technik', $this->payload($technik));
        $this->assertSame('Vorschaubild zum be real Video', $this->payload($video));
        $this->assertStringNotContainsStringIgnoringCase('bontus', $this->payload($makler).$this->payload($technik));
    }

    public function test_alt_texts_edited_in_the_meantime_are_left_alone(): void
    {
        $edited = $this->setting('translatable_makler', 'cta_image_alt_de', 'Das Team vor dem Büro');

        $this->runMigration();

        $this->assertSame('Das Team vor dem Büro', $this->payload($edited));
    }

    public function test_the_gender_spelling_of_the_contact_intro_is_unified(): void
    {
        $id = $this->setting('translatable_contact', 'contactpersons_introtext_de', '<p>Hier stellen wir die zuständigen MitarbeiterInnen vor.</p>');

        $this->runMigration();

        $this->assertSame('<p>Hier stellen wir die zuständigen Mitarbeiter:innen vor.</p>', $this->payload($id));
    }

    public function test_non_question_faq_entries_become_questions_and_the_garbled_source_is_fixed(): void
    {
        $energy = QuestionFactory::new()->create(['question' => ['de' => 'So einfach wechseln Sie Ihren Stromanbieter in Österreich', 'en' => ''], 'answer' => ['de' => '<p>Antwort</p>', 'en' => '']]);
        $short = QuestionFactory::new()->create([
            'question' => ['de' => 'Kurzzeitvermietung in der EU: Was die neuen Regeln für Sie jetzt bedeuten', 'en' => ''],
            'answer' => ['de' => '<p>in Österreich also frühestens ab Mai 2026 European CommissionRat der Europäischen Union.</p>', 'en' => ''],
        ]);
        $slug = $short->slug;

        $this->runMigration();

        $this->assertSame('Wie wechsle ich meinen Stromanbieter in Österreich?', Question::find($energy->id)->getTranslation('question', 'de'));
        $short = Question::find($short->id);
        $this->assertSame('Was bedeuten die neuen EU-Regeln zur Kurzzeitvermietung für mich?', $short->getTranslation('question', 'de'));
        $this->assertStringContainsString('ab Mai 2026 (Quelle: Rat der Europäischen Union).', $short->getTranslation('answer', 'de'));
        $this->assertStringNotContainsString('CommissionRat', $short->getTranslation('answer', 'de'));
        $this->assertSame($slug, $short->slug);
    }

    public function test_the_reference_title_gets_the_correct_street_spelling(): void
    {
        $reference = ReferenceFactory::new()->create(['title' => ['de' => 'Triester Strasse', 'en' => 'Triester Strasse']]);
        DB::table('references')->where('id', $reference->id)->update(['slug' => 'triester-strasse']);

        $this->runMigration();

        $reference = Reference::find($reference->id);
        $this->assertSame('Triester Straße', $reference->getTranslation('title', 'de'));
        $this->assertSame('Triester Straße', $reference->getTranslation('title', 'en'));
    }

    public function test_references_show_no_hashtags(): void
    {
        $reference = ReferenceFactory::new()->create(['tags' => json_encode(['#lovely #farbtupfer', 'restauriert'])]);

        $html = view('components.swiper-slide', ['ref' => $reference])->render();

        $this->assertStringNotContainsString('#', $html);
        $this->assertStringNotContainsString('farbtupfer', $html);
    }

    public function test_the_faq_page_shows_the_date_of_the_last_review_only_when_set(): void
    {
        $with = QuestionFactory::new()->create(['question' => ['de' => 'Mit Stand?', 'en' => ''], 'stand' => '2026-10-01']);
        $without = QuestionFactory::new()->create(['question' => ['de' => 'Ohne Stand?', 'en' => '']]);

        $this->get(route('faq.single', $with->slug))->assertOk()->assertSee('Stand: 01.10.2026');
        $this->get(route('faq.single', $without->slug))->assertOk()->assertDontSee('Stand:');
    }

    public function test_logos_have_alt_text(): void
    {
        $html = view('livewire.parts.preheader')->render();

        $this->assertDoesNotMatchRegularExpression('/<img(?![^>]*\balt=)[^>]*>/', $html);
    }
}
