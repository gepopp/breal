<?php

namespace Tests\Feature\Realty;

use App\Livewire\Realty\Contact;
use App\Models\Realty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_when_kontaktperson_has_no_anrede(): void
    {
        $realty = Realty::create([
            'title' => 'Helle 2,5-Zimmer-Wohnung',
            'data' => [
                'kontaktperson' => [
                    'vorname' => 'Tanja',
                    'name' => 'Bachschwöller',
                    'email_direkt' => 'vertrieb@bereal-makler.at',
                ],
            ],
        ]);

        Livewire::test(Contact::class, ['realty' => $realty])
            ->assertOk()
            ->assertSee('Tanja')
            ->assertSee('Bachschwöller')
            ->assertSee('vertrieb@bereal-makler.at');
    }

    public function test_renders_when_kontaktperson_has_no_email(): void
    {
        $realty = Realty::create([
            'title' => 'Dachgeschosswohnung mit Terrasse',
            'data' => [
                'kontaktperson' => [
                    'vorname' => 'Sebastian',
                    'name' => 'Kampf',
                ],
            ],
        ]);

        Livewire::test(Contact::class, ['realty' => $realty])
            ->assertOk()
            ->assertSee('Sebastian')
            ->assertDontSee('mailto:"');
    }

    public function test_renders_anrede_when_present(): void
    {
        $realty = Realty::create([
            'title' => 'Generalsanierte Altbauwohnung',
            'data' => [
                'kontaktperson' => [
                    'anrede' => 'Frau',
                    'vorname' => 'Tanja',
                    'name' => 'Bachschwöller',
                    'email_direkt' => 'vertrieb@bereal-makler.at',
                ],
            ],
        ]);

        Livewire::test(Contact::class, ['realty' => $realty])
            ->assertOk()
            ->assertSee('Frau');
    }
}
