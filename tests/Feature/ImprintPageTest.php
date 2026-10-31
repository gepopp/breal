<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImprintPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_imprint_names_the_austrian_legal_basis_instead_of_tmg(): void
    {
        $response = $this->get('/impressum')->assertOk();

        $response->assertSee('§ 5 ECG', false);
        $response->assertSee('§ 14 UGB', false);
        $response->assertSee('§ 25 MedienG', false);
        $response->assertDontSee('TMG');
    }

    public function test_imprint_lists_all_three_companies_with_their_data(): void
    {
        $response = $this->get('/impressum')->assertOk();

        foreach ([
            'be real Immobilienmanagement GmbH', 'FN 152672 v', 'ATU49490402', 'office@bereal-immobilien.at',
            'be real Immobilienmakler GmbH', 'FN 556967 w', 'ATU76971617', 'office@bereal-makler.at', 'Tanja Bachschwöller-Eybel',
            'be real Baumanagement GmbH', 'FN 660246 a', 'ATU82490109', 'office@bereal-technik.at', 'Thomas Keinrath',
            'Lukas Eybel', '+43 1 535 36 19', '+43 1 535 64 28',
        ] as $fact) {
            $response->assertSee($fact, false);
        }
    }

    public function test_imprint_uses_the_correct_address_and_no_outdated_data(): void
    {
        $response = $this->get('/impressum')->assertOk();

        $response->assertSee('Franz-Josefs-Kai 65/Mezzanin, 1010 Wien', false);

        foreach (['Franz Josefs Kai', 'Franz-Josef-Kai', 'Immobilienmanagment', 't.keinrath@', 'Bontus', 'Informed'] as $outdated) {
            $response->assertDontSee($outdated, false);
        }
    }

    public function test_media_owner_disclosure_names_the_company_and_its_address(): void
    {
        $this->get('/impressum')
            ->assertOk()
            ->assertSee('Medieninhaber und Herausgeber dieser Website ist die be real Immobilienmanagement GmbH, Franz-Josefs-Kai 65/Mezzanin, 1010 Wien.', false);
    }

    public function test_details_the_client_has_not_delivered_yet_are_not_printed(): void
    {
        $this->get('/impressum')
            ->assertOk()
            ->assertDontSee('Firmenbuchgericht')
            ->assertDontSee('Aufsichtsbehörde')
            ->assertDontSee('Kammer:', false)
            ->assertDontSee('Gewerbe:', false)
            ->assertDontSee('Grundlegende Richtung');
    }

    public function test_delivered_details_are_printed_once_they_are_configured(): void
    {
        config([
            'company.sections.Makler.firmenbuchgericht' => 'Handelsgericht Wien',
            'company.sections.Makler.gewerbe' => 'Immobilienmakler',
            'company.sections.Makler.kammer' => 'Wirtschaftskammer Wien',
            'company.sections.Makler.aufsichtsbehoerde' => 'Magistrat der Stadt Wien',
            'company.media.editorial_line' => 'Information über die Leistungen von be real Immobilien.',
        ]);

        $this->get('/impressum')
            ->assertOk()
            ->assertSee('Firmenbuchgericht:', false)
            ->assertSee('Handelsgericht Wien')
            ->assertSee('Gewerbe:', false)
            ->assertSee('Immobilienmakler')
            ->assertSee('Wirtschaftskammer Wien')
            ->assertSee('Magistrat der Stadt Wien')
            ->assertSee('Grundlegende Richtung:', false);
    }

    public function test_imprint_has_one_h1_and_no_longer_mentions_the_discontinued_eu_platform(): void
    {
        $html = $this->get('/impressum')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringNotContainsString('ec.europa.eu/consumers/odr', $html);
        $this->assertStringContainsString('href="mailto:office@bereal-technik.at"', $html);
        $this->assertStringContainsString('href="tel:+4315353619"', $html);
    }

    public function test_english_imprint_is_translated(): void
    {
        $response = $this->get('/en/impressum')->assertOk();

        $response->assertSee('Legal notice');
        $response->assertSee('Commercial register number');
        $response->assertSee('Managing directors');
        $response->assertSee('be real Baumanagement GmbH');
        $response->assertDontSee('Geschäftsführung');
        $response->assertDontSee('Angaben gemäß');
    }
}
