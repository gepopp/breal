<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel('admin');
        $this->actingAs(User::factory()->create(['admin' => true]));
    }

    public function test_admin_can_change_name_and_email_of_another_user(): void
    {
        $user = User::factory()->create(['admin' => false]);

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->fillForm([
                'name' => 'Neuer Name',
                'email' => 'neu@example.com',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();
        $this->assertSame('Neuer Name', $user->name);
        $this->assertSame('neu@example.com', $user->email);
    }

    public function test_admin_can_reset_password_of_another_user(): void
    {
        $user = User::factory()->create();

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->fillForm(['password' => 'ein-neues-passwort'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(Hash::check('ein-neues-passwort', $user->refresh()->password));
    }

    public function test_blank_password_keeps_existing_password(): void
    {
        $user = User::factory()->create();
        $oldHash = $user->password;

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->fillForm(['name' => 'Anderer Name', 'password' => ''])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame($oldHash, $user->refresh()->password);
    }

    public function test_email_is_required(): void
    {
        $user = User::factory()->create();

        Livewire::test(EditUser::class, ['record' => $user->id])
            ->fillForm(['email' => ''])
            ->call('save')
            ->assertHasFormErrors(['email' => 'required']);
    }
}
