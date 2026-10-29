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

    public function test_bold_only_paragraph_ending_with_colon_becomes_a_heading(): void
    {
        $this->assertSame('<h3><strong>Unsere Leistungen:</strong></h3>', HtmlLists::normalize('<p><strong>Unsere Leistungen:</strong></p>'));
        $this->assertSame('<p><strong>Wichtig</strong></p>', HtmlLists::normalize('<p><strong>Wichtig</strong></p>'));
    }

    public function test_real_lists_stay_untouched(): void
    {
        $html = '<ul><li><p>Eins</p></li></ul>';

        $this->assertSame($html, HtmlLists::normalize($html));
    }
}
