<?php

namespace Tests\Feature;

use App\Livewire\Pages\Immobiliensuche;
use App\Models\Realty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MobilePropertyListTest extends TestCase
{
    use RefreshDatabase;

    private function realty(string $title, int $price): Realty
    {
        return Realty::create([
            'title' => $title, 'slug' => str($title)->slug()->toString(), 'objektnr_extern' => '1/'.$price,
            'nutzungsart' => 'WOHNEN', 'vermarktungsart' => 'kauf', 'zimmer' => 3, 'wohnflaeche' => 71.5,
            'preis' => $price, 'plz' => '1010', 'ort' => 'Wien', 'data' => [],
        ]);
    }

    public function test_the_search_offers_a_card_list_for_small_screens_next_to_the_table(): void
    {
        $this->realty('Helle Wohnung', 350000);

        $html = $this->get('/makler/immobiliensuche')->assertOk()->getContent();

        $this->assertStringContainsString('<ul class="divide-y', $html);
        $this->assertStringContainsString('class="md:hidden"', $html);
        $this->assertStringContainsString('class="hidden md:block"', $html);
        $this->assertStringContainsString('for="realty-sort"', $html);
        $this->assertMatchesRegularExpression('/<li wire:key="card_\d+" class="py-4">\s*<a href="[^"]+immobilie\/helle-wohnung"/', $html);
        $this->assertStringContainsString('1010 Wien', $html);
        $this->assertStringContainsString('71,50 m²', $html);
    }

    public function test_the_sort_menu_of_the_card_list_sorts_and_ignores_unknown_values(): void
    {
        $this->realty('Teure Wohnung', 900000);
        $this->realty('Günstige Wohnung', 100000);

        $component = Livewire::test(Immobiliensuche::class)->call('sortByOption', 'preis:asc');

        $this->assertSame(['Günstige Wohnung', 'Teure Wohnung'], $component->instance()->realties->pluck('title')->all());

        $component->call('sortByOption', 'preis:desc');
        $this->assertSame(['Teure Wohnung', 'Günstige Wohnung'], $component->instance()->realties->pluck('title')->all());

        $component->call('sortByOption', 'password:asc')->assertSet('sortBy', 'preis')
            ->call('sortByOption', 'preis:sideways')->assertSet('sortDirection', 'desc');
    }
}
