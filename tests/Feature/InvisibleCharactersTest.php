<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InvisibleCharactersTest extends TestCase
{
    use RefreshDatabase;

    public function test_zero_width_characters_are_removed_from_settings_and_faq_texts(): void
    {
        DB::table('settings')->insert(['group' => 'test', 'name' => 'invisible_plain', 'locked' => false, 'payload' => json_encode("Zufrieden\u{200B}heit \u{FEFF}Text", JSON_UNESCAPED_UNICODE)]);
        DB::table('settings')->insert(['group' => 'test', 'name' => 'invisible_escaped', 'locked' => false, 'payload' => json_encode("A\u{200B}B\u{2060}C")]);
        DB::table('questions')->insert(['slug' => 'q', 'question' => json_encode(['de' => 'Frage']), 'answer' => json_encode(['de' => "<p>Antwort.\u{200B} Mehr</p>"])]);

        $migration = require database_path('migrations/2026_11_01_200000_remove_invisible_characters_from_texts.php');
        $migration->up();
        $migration->up();

        $this->assertSame('Zufriedenheit Text', json_decode(DB::table('settings')->where('name', 'invisible_plain')->value('payload')));
        $this->assertSame('ABC', json_decode(DB::table('settings')->where('name', 'invisible_escaped')->value('payload')));
        $this->assertSame('<p>Antwort. Mehr</p>', json_decode(DB::table('questions')->where('slug', 'q')->value('answer'), true)['de']);
        $this->assertSame('Frage', json_decode(DB::table('questions')->where('slug', 'q')->value('question'), true)['de']);
    }
}
