<?php

namespace App\Jobs;

use App\Models\Realty;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CleanupRealtiesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $realties = Realty::where('updated_at', '<', now()->subHours(4))->get();
        foreach ($realties as $realty) {
            if(\Storage::disk('public')->exists($realty->path)){
                \Storage::disk('public')->delete($realty->path);
            }
            $realty->delete();
        }
    }
}
