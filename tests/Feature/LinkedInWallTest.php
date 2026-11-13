<?php

namespace Tests\Feature;

use App\Livewire\Parts\LinkeInWall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class LinkedInWallTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_with_only_a_profile_url_does_not_break_the_start_page(): void
    {
        // The admin profile form saves `linkedin.url` only, without token and expiry.
        User::factory()->create(['linkedin' => ['url' => 'https://www.linkedin.com/in/example']]);

        Livewire::test(LinkeInWall::class)->assertSet('user', null);

        $this->get('/')->assertOk();
    }

    public function test_a_user_with_an_expired_token_is_not_used(): void
    {
        User::factory()->create(['linkedin' => ['token' => 'abc', 'expiresIn' => now()->subDay()->toDateTimeString()]]);

        Livewire::test(LinkeInWall::class)->assertSet('user', null);
    }

    public function test_a_user_with_a_valid_token_is_used(): void
    {
        $user = User::factory()->create(['linkedin' => ['token' => 'abc', 'expiresIn' => now()->addDay()->toDateTimeString()]]);

        Http::fake(['*' => Http::response([], 500)]);

        Livewire::test(LinkeInWall::class)->assertSet('user.id', $user->id);
    }
}
