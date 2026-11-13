<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\EditProfile;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditProfileLinkedInTest extends TestCase
{
    use RefreshDatabase;

    public function test_saving_the_profile_keeps_the_stored_linkedin_token(): void
    {
        $expiresIn = now()->addDays(30)->toDateTimeString();
        $user = User::factory()->create(['admin' => true, 'linkedin' => ['token' => 'abc', 'expiresIn' => $expiresIn]]);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');

        Livewire::test(EditProfile::class)
            ->fillForm(['name' => 'New Name', 'linkedin' => ['url' => 'https://www.linkedin.com/in/example']])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();

        $this->assertSame('New Name', $user->name);
        $this->assertSame('https://www.linkedin.com/in/example', $user->linkedin['url']);
        $this->assertSame('abc', $user->linkedin['token']);
        $this->assertSame($expiresIn, $user->linkedin['expiresIn']);
    }

    public function test_saving_the_profile_without_a_linkedin_connection_stores_only_the_url(): void
    {
        $user = User::factory()->create(['admin' => true]);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');

        Livewire::test(EditProfile::class)
            ->fillForm(['linkedin' => ['url' => 'https://www.linkedin.com/in/example']])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(['url' => 'https://www.linkedin.com/in/example'], $user->refresh()->linkedin);
    }
}
