<?php

namespace Tests\Feature;

use App\Providers\AppServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class ConsoleSettingsTest extends TestCase
{
    private string $originalMemoryLimit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalMemoryLimit = (string) ini_get('memory_limit');
    }

    protected function tearDown(): void
    {
        ini_set('memory_limit', $this->originalMemoryLimit);

        parent::tearDown();
    }

    public function test_memory_limit_is_raised_when_it_is_lower_than_wanted(): void
    {
        ini_set('memory_limit', '128M');

        AppServiceProvider::raiseMemoryLimit('768M');

        $this->assertSame('768M', ini_get('memory_limit'));
    }

    public function test_memory_limit_is_never_lowered(): void
    {
        ini_set('memory_limit', '2G');

        AppServiceProvider::raiseMemoryLimit('768M');

        $this->assertSame('2G', ini_get('memory_limit'));
    }

    public function test_unlimited_memory_stays_unlimited(): void
    {
        ini_set('memory_limit', '-1');

        AppServiceProvider::raiseMemoryLimit('768M');

        $this->assertSame('-1', ini_get('memory_limit'));
    }

    public function test_invalid_wanted_value_changes_nothing(): void
    {
        ini_set('memory_limit', '128M');

        AppServiceProvider::raiseMemoryLimit('');
        AppServiceProvider::raiseMemoryLimit('lots');

        $this->assertSame('128M', ini_get('memory_limit'));
    }

    public function test_queue_worker_memory_limit_is_configured(): void
    {
        $this->assertSame('768M', config('queue.worker_memory_limit'));
    }

    public function test_only_the_hourly_import_is_scheduled(): void
    {
        $events = app(Schedule::class)->events();

        $this->assertCount(1, $events);
        $this->assertSame('0 * * * *', $events[0]->expression);
    }
}
