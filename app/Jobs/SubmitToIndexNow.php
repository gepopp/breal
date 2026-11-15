<?php

namespace App\Jobs;

use App\Services\IndexNow;
use App\Services\IndexNowNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

/**
 * Reports the addresses collected by IndexNowNotifier to IndexNow. Addresses that could not be sent are kept for
 * the retry.
 */
class SubmitToIndexNow implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * @return list<int>
     */
    public function backoff(): array
    {
        return [120, 600];
    }

    public function handle(IndexNowNotifier $notifier, IndexNow $indexNow): void
    {
        $urls = $notifier->takePending();

        if ($urls === []) {
            return;
        }

        try {
            $indexNow->submit($urls);
        } catch (Throwable $exception) {
            $notifier->restore($urls);

            throw $exception;
        }
    }
}
