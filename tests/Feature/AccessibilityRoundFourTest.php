<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibilityRoundFourTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_questions_are_buttons_that_expand_their_answer(): void
    {
        foreach ([1, 2] as $order) {
            DB::table('questions')->insert([
                'order' => $order,
                'question' => json_encode(['de' => "Frage $order?", 'en' => "Question $order?"]),
                'answer' => json_encode(['de' => "Antwort $order", 'en' => "Answer $order"]),
                'company' => 'hausverwaltung',
                'slug' => "frage-$order",
            ]);
        }

        $html = $this->get('/faq')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/<h2[^>]*>\s*<button type="button"[^>]*x-bind:aria-expanded="\(current == 0\)\.toString\(\)" aria-controls="faq-answer-0"/s', $html);
        $this->assertStringContainsString('id="faq-answer-1"', $html);
        $this->assertDoesNotMatchRegularExpression('/<p[^>]*x-on:click="current/', $html);
    }

    public function test_upload_area_is_a_button_with_text_in_the_page_language(): void
    {
        $german = $this->get('/kontakt')->assertOk()->getContent();
        $this->assertStringContainsString('Hier ablegen oder', $german);
        $this->assertStringContainsString('aria-label="Dateien zum Hochladen auswählen"', $german);
        $this->assertMatchesRegularExpression('/<button type="button" @click="\$refs\.input\.click\(\)"/', $german);

        $english = $this->get('/en/kontakt')->assertOk()->getContent();
        $this->assertStringContainsString('Drop files here or', $english);
        $this->assertStringContainsString('Up to', $english);
        foreach (['Hier ablegen', 'Dateien suchen', 'Zum Hochladen', 'Lade...', 'Bis zu'] as $german) {
            $this->assertStringNotContainsString($german, $english, $german);
        }
    }

    public function test_navigation_landmarks_have_distinct_names(): void
    {
        $this->get('/team')
            ->assertSee('<nav aria-label="Hauptnavigation"', false)
            ->assertSee('<nav aria-label="Fußbereich"', false);
        $this->get('/en/team')
            ->assertSee('<nav aria-label="Main navigation"', false)
            ->assertSee('<nav aria-label="Footer navigation"', false);
    }

    public function test_pagination_labels_are_plain_text_and_disabled_arrows_carry_no_label(): void
    {
        $this->assertSame('Zurück', __('pagination.previous'));
        $this->assertSame('Weiter', __('pagination.next'));
        app()->setLocale('en');
        $this->assertSame('Previous', __('pagination.previous'));
        $this->assertSame('Next', __('pagination.next'));

        $view = file_get_contents(resource_path('views/flux/pagination.blade.php'));
        $this->assertStringNotContainsString('aria-disabled="true" aria-label', $view);
        $this->assertStringNotContainsString('<div aria-label', $view);
    }
}
