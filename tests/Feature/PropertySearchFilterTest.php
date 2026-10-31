<?php

namespace Tests\Feature;

use App\Livewire\Pages\Immobiliensuche;
use App\Models\Realty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PropertySearchFilterTest extends TestCase
{
    use RefreshDatabase;

    private function realty(string $slug, int $price): void
    {
        Realty::create(['title' => $slug, 'slug' => $slug, 'preis' => $price, 'nutzungsart' => 'wohnen', 'vermarktungsart' => 'kauf', 'data' => []]);
    }

    public function test_max_price_filter_hides_more_expensive_properties(): void
    {
        $this->realty('guenstig', 100000);
        $this->realty('teuer', 990000);

        Livewire::test(Immobiliensuche::class)
            ->set('filters.price_max', 150000)
            ->assertSee('guenstig')
            ->assertDontSee('teuer');
    }
}
