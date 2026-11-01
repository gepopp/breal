<?php

namespace Tests\Unit;

use App\Services\HtmlLists;
use PHPUnit\Framework\TestCase;

class HtmlListsTest extends TestCase
{
    public function test_bullet_paragraph_becomes_a_list(): void
    {
        $html = '<p>Intro</p><p>• Eins<br>• Zwei&nbsp;<br>• Drei .</p>';

        $this->assertSame('<p>Intro</p><ul><li>Eins</li><li>Zwei</li><li>Drei</li></ul>', HtmlLists::normalize($html));
    }

    public function test_consecutive_middle_dot_paragraphs_become_one_list(): void
    {
        $html = '<p><strong>Profil:</strong></p><p>·&nbsp;&nbsp;&nbsp; Erfahrung</p><p>· Deutsch</p><p></p><p>Danach</p><p>· Neu</p>';

        $this->assertSame(
            '<h2><strong>Profil:</strong></h2><ul><li>Erfahrung</li><li>Deutsch</li></ul><p>Danach</p><ul><li>Neu</li></ul>',
            HtmlLists::normalize($html)
        );
    }

    public function test_bold_only_paragraph_ending_with_colon_becomes_a_heading(): void
    {
        $this->assertSame('<h2><strong>Unsere Leistungen:</strong></h2>', HtmlLists::normalize('<p><strong>Unsere Leistungen:</strong></p>'));
        $this->assertSame('<p><strong>Wichtig</strong></p>', HtmlLists::normalize('<p><strong>Wichtig</strong></p>'));
    }

    public function test_real_lists_stay_untouched(): void
    {
        $html = '<ul><li><p>Eins</p></li></ul>';

        $this->assertSame($html, HtmlLists::normalize($html));
    }

    public function test_a_hand_written_h3_without_any_h2_becomes_an_h2(): void
    {
        $this->assertSame('<p>Text</p><h2>Leistungen</h2><ul><li>Eins</li></ul>', HtmlLists::normalize('<p>Text</p><h3>Leistungen</h3><ul><li>Eins</li></ul>'));
        $this->assertSame('<h2>A</h2><h3>B</h3>', HtmlLists::normalize('<h2>A</h2><h3>B</h3>'));
    }
}
