<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Services\HtmlTrailingSpace;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FaqAnswerTrailingSpaceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function texts(): array
    {
        return [
            'empty paragraphs' => ['<p>Text</p><p><br></p><p><br></p>', '<p>Text</p>'],
            'empty paragraph after list' => ['<ul><li>Eins</li></ul><p><br></p>', '<ul><li>Eins</li></ul>'],
            'nbsp in last paragraph' => ['<p>Text.&nbsp;</p>', '<p>Text.</p>'],
            'br and nbsp in last paragraph' => ['<p><br>Text.<br>&nbsp;</p>', '<p><br>Text.</p>'],
            'double br' => ['<p>Text.<br><br></p>', '<p>Text.</p>'],
            'whitespace after the last block' => ["<p>Text.</p>\n ", '<p>Text.</p>'],
            'clean text stays' => ['<p>Eins</p><p>Zwei</p>', '<p>Eins</p><p>Zwei</p>'],
            'empty paragraph in the middle stays' => ['<p>Eins</p><p><br></p><p>Zwei</p>', '<p>Eins</p><p><br></p><p>Zwei</p>'],
            'plain text' => ['Nur Text', 'Nur Text'],
        ];
    }

    #[DataProvider('texts')]
    public function test_trailing_empty_markup_is_removed(string $input, string $expected): void
    {
        $this->assertSame($expected, HtmlTrailingSpace::trim($input));
    }

    public function test_saving_a_question_trims_every_language_of_the_answer(): void
    {
        $question = QuestionFactory::new()->create([
            'answer' => [
                'de' => '<p>Antwort.&nbsp;</p><p><br></p>',
                'en' => '<p>Answer.</p><p><br></p><p><br></p>',
            ],
        ]);

        $question->refresh();

        $this->assertSame('<p>Antwort.</p>', $question->getTranslation('answer', 'de'));
        $this->assertSame('<p>Answer.</p>', $question->getTranslation('answer', 'en'));
    }

    public function test_updating_a_question_trims_the_new_answer(): void
    {
        $question = QuestionFactory::new()->create(['answer' => ['de' => '<p>Sauber.</p>']]);

        $question->update(['answer' => ['de' => '<p>Neu.</p><p><br></p>']]);

        $this->assertSame('<p>Neu.</p>', Question::find($question->id)->getTranslation('answer', 'de'));
    }
}
