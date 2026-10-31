<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_offers_a_skip_link_to_the_main_landmark(): void
    {
        $response = $this->get(route('hausverwaltung.team'));

        $response->assertOk();
        $response->assertSee('href="#main"', false);
        $response->assertSee('<main id="main"', false);
    }

    public function test_mobile_menu_toggle_is_a_labelled_button_with_expanded_state(): void
    {
        $response = $this->get(route('hausverwaltung.team'));

        $response->assertSee('aria-controls="mobile-menu"', false);
        $response->assertSee('x-bind:aria-expanded="open.toString()"', false);
        $response->assertSee('aria-label="'.__('navigation.menu').'"', false);
        $response->assertSee('id="mobile-menu"', false);
    }

    public function test_sent_confirmation_is_inert_until_the_form_was_sent(): void
    {
        $response = $this->get(route('hausverwaltung.kontakt'));

        $response->assertOk();
        $response->assertSee('x-bind:inert="!is_sent"', false);
        $response->assertSee('x-bind:inert="is_sent"', false);
    }
}
