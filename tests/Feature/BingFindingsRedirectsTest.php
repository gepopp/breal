<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Findings of the Bing Webmaster Tools check of 2026-11-12: "/index.php/..." answered with the page (every page twice,
 * with a self canonical) and old addresses of the website before the relaunch answered 404 although they still get
 * impressions.
 */
class BingFindingsRedirectsTest extends TestCase
{
    use RefreshDatabase;

    private function root(): string
    {
        return rtrim(config('app.url'), '/');
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function indexPhpAddresses(): array
    {
        return [
            'start page' => ['/index.php', '/'],
            'start page with query' => ['/index.php?x=1', '/?x=1'],
            'service' => ['/index.php/service', '/service'],
            'nested' => ['/index.php/technik/leistungen', '/technik/leistungen'],
            'with query' => ['/index.php/service?tab=meldezettel', '/service?tab=meldezettel'],
            'english' => ['/index.php/en/kontakt', '/en/kontakt'],
        ];
    }

    #[DataProvider('indexPhpAddresses')]
    public function test_index_php_addresses_redirect_permanently_to_the_address_without_it(string $from, string $to): void
    {
        $this->get($from)->assertStatus(301)->assertRedirect($this->root().$to);
    }

    public function test_index_php_in_the_middle_of_a_path_is_left_alone(): void
    {
        $this->get('/service?next=/index.php/service')->assertOk();
    }

    public function test_index_php_is_not_redirected_for_other_methods(): void
    {
        $this->post('/index.php/service')->assertStatus(405);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function oldAddresses(): array
    {
        return [
            'immobilien' => ['/immobilien', '/makler/immobiliensuche'],
            'immobilien start' => ['/immobilien/start', '/makler'],
            'miete' => ['/miete', '/makler/immobiliensuche'],
            'verwalterservice' => ['/verwalterservice', '/unternehmensservice'],
            'ueber_uns' => ['/ueber_uns', '/'],
            'ueber_uns team' => ['/ueber_uns/team', '/team'],
            'english prefix is kept' => ['/en/verwalterservice', '/en/unternehmensservice'],
        ];
    }

    #[DataProvider('oldAddresses')]
    public function test_old_addresses_redirect_permanently_to_their_new_page(string $from, string $to): void
    {
        $this->get($from)->assertStatus(301)->assertRedirect($this->root().$to);
    }

    public function test_the_targets_of_the_old_addresses_exist(): void
    {
        foreach (['/makler/immobiliensuche', '/makler', '/unternehmensservice', '/team'] as $path) {
            $this->get($path)->assertOk();
        }
    }
}
