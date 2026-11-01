<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class LlmsTxtTest extends TestCase
{
    private const HOST = 'https://www.bereal-immobilien.at';

    public function test_llms_txt_follows_the_expected_structure(): void
    {
        $text = $this->llmsTxt();

        $this->assertMatchesRegularExpression('/^# be real Immobilien$/m', $text);
        $this->assertMatchesRegularExpression('/^> .{50,}/m', $text);

        foreach (['Gesellschaften', 'Leistungen', 'Kontakt', 'Optional'] as $section) {
            $this->assertMatchesRegularExpression('/^## '.$section.'$/m', $text);
        }
    }

    public function test_llms_txt_states_the_company_facts_consistently(): void
    {
        $text = $this->llmsTxt();

        foreach ([
            'be real Immobilienmanagement GmbH',
            'be real Immobilienmakler GmbH',
            'be real Baumanagement GmbH',
            'seit fast 30 Jahren',
            'Franz-Josefs-Kai 65/Mezzanin, 1010 Wien',
            '+43 1 535 36 19',
            'Mo–Do 8–16 Uhr, Fr 8–12 Uhr',
            'Mo–Do 9–12 und 13–16 Uhr, Fr 9–12 Uhr',
            'office@bereal-immobilien.at',
            'office@bereal-makler.at',
            'office@bereal-technik.at',
        ] as $fact) {
            $this->assertStringContainsString($fact, $text);
        }

        $this->assertStringNotContainsStringIgnoringCase('Bontus', $text);
        $this->assertStringNotContainsString('Franz Josefs Kai', $text);
        $this->assertStringNotContainsString('Franz-Josef-Kai', $text);
    }

    public function test_every_link_points_to_an_existing_page(): void
    {
        preg_match_all('#\]\('.preg_quote(self::HOST, '#').'(/[^)\s]*)?\)#', $this->llmsTxt(), $matches);

        $paths = array_unique(array_map(fn (string $path): string => $path === '' ? '/' : $path, $matches[1]));

        $this->assertNotEmpty($paths);

        foreach ($paths as $path) {
            if ($path === '/sitemap.xml') {
                $this->assertTrue(RouteFacade::has('sitemap'));

                continue;
            }

            $pagePath = preg_replace('#^/en(?=/|$)#', '', $path) ?: '/';

            $this->assertTrue($this->isRealPage($pagePath), "llms.txt links to {$path}, which is not a page of the site.");
        }
    }

    private function isRealPage(string $path): bool
    {
        try {
            $route = $this->app['router']->getRoutes()->match(Request::create($path));
        } catch (NotFoundHttpException) {
            return false;
        }

        return $route instanceof Route && ! $route->isFallback;
    }

    private function llmsTxt(): string
    {
        $this->assertFileExists(public_path('llms.txt'));

        return file_get_contents(public_path('llms.txt'));
    }

    public function test_technik_services_are_linked_directly_and_not_through_a_redirect(): void
    {
        $text = $this->llmsTxt();

        foreach (['bauwerksbuch', 'onorm-b-1300-b-1301', 'objektbegehungen', 'energieausweis', 'fordermanagement', 'sanierungskonzepte'] as $slug) {
            $this->assertStringContainsString("/technik/leistung/{$slug})", $text);
            $this->assertStringNotContainsString("bereal-immobilien.at/leistung/{$slug})", $text);
        }

        $this->assertStringContainsString('/leistung/bauverwaltung)', $text);
    }
}
