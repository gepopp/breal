<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Filament\Resources\CompetenceResource\Pages\EditCompetence;
use App\Models\User;
use Database\Factories\CompetenceFactory;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompetenceMetaDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_admin_form_has_a_meta_description_for_both_languages(): void
    {
        $competence = CompetenceFactory::new()->create(['company' => CompaniesEnum::Technik]);

        $this->actingAs(User::factory()->create(['admin' => true]));
        Filament::setCurrentPanel('admin');

        Livewire::test(EditCompetence::class, ['record' => $competence->getRouteKey()])
            ->assertFormFieldExists('meta_description.de')
            ->assertFormFieldExists('meta_description.en');
    }

    public function test_the_meta_description_is_translatable_and_stored_per_language(): void
    {
        $competence = CompetenceFactory::new()->create([
            'meta_description' => ['de' => 'Deutsch', 'en' => 'English'],
        ]);

        $this->assertSame('Deutsch', $competence->fresh()->getTranslation('meta_description', 'de'));
        $this->assertSame('English', $competence->fresh()->getTranslation('meta_description', 'en'));
    }
}
