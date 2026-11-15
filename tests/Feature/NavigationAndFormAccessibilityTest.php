<?php

namespace Tests\Feature;

use App\Services\MainNavigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationAndFormAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_mobile_menu_offers_the_faq_like_the_desktop_menu(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $mobile = substr($html, strpos($html, 'id="mobile-menu"'));

        $this->assertStringContainsString('href="'.route('hausverwaltung.faq').'"', substr($html, 0, strpos($html, 'id="mobile-menu"')));
        $this->assertStringContainsString('href="'.route('hausverwaltung.faq').'">FAQ</a>', $mobile);
    }

    public function test_both_menus_are_built_from_the_same_definition(): void
    {
        foreach (['hausverwaltung', 'makler', 'technik'] as $company) {
            $items = collect(MainNavigation::items($company));

            $this->assertTrue($items->every(fn (array $item): bool => $item['desktop'] || $item['mobile']), $company);
            $this->assertSame(
                $items->where('desktop')->where('mobile')->pluck('route')->all(),
                $items->where('desktop')->pluck('route')->intersect($items->where('mobile')->pluck('route'))->values()->all(),
                $company,
            );
        }

        $mobileOnly = collect(MainNavigation::items('hausverwaltung'))->where('desktop', false)->pluck('route')->all();
        $this->assertSame(['hausverwaltung.kontakt', 'verwalterservice'], $mobileOnly);
    }

    public function test_every_company_menu_keeps_its_links_on_both_menus(): void
    {
        foreach (['/' => 'hausverwaltung.team', '/makler' => 'makler.team', '/technik' => 'technik.team'] as $uri => $route) {
            $html = $this->get($uri)->assertOk()->getContent();

            $this->assertSame(2, substr_count($html, 'href="'.route($route).'"'), $uri);
        }
    }

    public function test_contact_form_names_the_purpose_of_personal_data_fields(): void
    {
        $html = $this->get('/kontakt')->assertOk()->getContent();

        foreach (['given-name', 'family-name', 'email', 'tel'] as $token) {
            $this->assertStringContainsString('autocomplete="'.$token.'"', $html, $token);
        }
    }

    public function test_form_field_borders_have_a_darker_rule_than_the_flux_default(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString('border-color: #858585;', $css);
    }
}
