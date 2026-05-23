<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class LoggingConfigTest extends TestCase
{
    public function test_default_log_channel_respects_log_channel_env(): void
    {
        $this->assertSame(env('LOG_CHANNEL', 'stack'), config('logging.default'));
        $this->assertNotSame(
            'slack',
            config('logging.default'),
            'The default log channel must not be hardcoded to slack; it should fall back to the LOG_CHANNEL env value.'
        );
    }

    public function test_default_log_channel_resolves_without_error(): void
    {
        $this->assertInstanceOf(LoggerInterface::class, Log::channel(config('logging.default')));
    }

    public function test_slack_logging_channel_is_removed(): void
    {
        $this->assertNull(config('logging.channels.slack'));
    }
}
