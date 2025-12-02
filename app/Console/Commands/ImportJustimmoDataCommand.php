<?php

namespace App\Console\Commands;

use App\Justimmo\Importer;
use Illuminate\Console\Command;

class ImportJustimmoDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'justimmo:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Justimmo real estate data from uploaded zip files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Justimmo import...');
        $this->newLine();

        try {
            Importer::import();

            $this->info('✓ Import completed successfully');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Import failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return self::FAILURE;
        }
    }
}
