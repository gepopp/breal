<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SubpageRenderingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function pages(): array
    {
        return [
            'Team' => ['/team'],
            'Kontakt' => ['/kontakt'],
            'FAQ' => ['/faq'],
            'Karriere' => ['/karriere'],
            'Leistungen' => ['/leistungen'],
            'Technik Team' => ['/technik/team'],
        ];
    }

    #[DataProvider('pages')]
    public function test_the_intro_text_of_a_subpage_is_not_faded_in(string $path): void
    {
        $this->get($path)->assertOk()->assertDontSee('data-aos="fade" data-aos-delay="600" class="prose', false);
    }

    public function test_the_footer_links_are_big_enough_to_hit(): void
    {
        $html = $this->get('/impressum')->assertOk()->getContent();
        $footer = substr($html, strpos($html, '<footer'));

        foreach (['/datenschutz"', '/barrierefreiheit"', '/impressum"'] as $link) {
            $this->assertMatchesRegularExpression('~<a class="[^"]*py-1\.5[^"]*" href="[^"]*'.$link.'~', $footer, $link);
        }
        $this->assertMatchesRegularExpression('~<button type="submit" class="[^"]*py-1\.5~', $footer);
        $this->assertSame(4, substr_count($footer, 'whitespace-nowrap'), 'A footer link breaks into two lines at medium widths');
    }
}
