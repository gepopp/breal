<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class LogViewerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_may_view_the_log_viewer(): void
    {
        $admin = User::factory()->create(['admin' => true]);

        $this->assertTrue(Gate::forUser($admin)->allows('viewLogViewer'));
    }

    public function test_non_admin_users_may_not_view_the_log_viewer(): void
    {
        $user = User::factory()->create(['admin' => false]);

        $this->assertFalse(Gate::forUser($user)->allows('viewLogViewer'));
    }

    public function test_guests_may_not_view_the_log_viewer(): void
    {
        $this->assertFalse(Gate::forUser(null)->allows('viewLogViewer'));
    }
}
