<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * An invalid sidebar state in the browser's localStorage made the admin unusable for one user.
 */
class AdminSidebarStorageGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_admin_pages_repair_an_invalid_sidebar_state_before_filament_reads_it(): void
    {
        $html = $this->get('/admin/login')->assertOk()->getContent();

        $guard = strpos($html, 'localStorage.removeItem(key)');

        $this->assertNotFalse($guard, 'The repair script is missing in the admin pages');
        $this->assertStringContainsString('filament:sidebar:collapsed_groups', $html);
        $this->assertStringContainsString("'collapsedGroups'", $html);
    }
}
