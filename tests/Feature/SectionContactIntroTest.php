<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionContactIntroTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_contact_intro_names_the_section_of_the_page(): void
    {
        $this->get('/makler')->assertOk()->assertSee('Unser Team von be real Makler steht Ihnen')->assertDontSee('be real Immobilienmanagement steht');
        $this->get('/technik')->assertOk()->assertSee('Unser Team von be real Technik steht Ihnen');
        $this->get('/en/makler')->assertOk()->assertSee('Our team at be real Makler is at your disposal');
        $this->get('/')->assertOk()->assertSee('be real Immobilienmanagement steht Ihnen');
    }
}
