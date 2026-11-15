<?php

namespace App\Console\Commands;

use App\Services\IndexNow;
use App\Services\SitemapBuilder;
use Illuminate\Console\Command;
use Spatie\Sitemap\Tags\Url;

class IndexNowSubmit extends Command
{
    protected $signature = 'indexnow:submit {--dry-run : only list the addresses}';

    protected $description = 'Reports every address of the sitemap to IndexNow (for example once after the first deploy)';

    public function handle(IndexNow $indexNow, SitemapBuilder $builder): int
    {
        $urls = collect($builder->build()->getTags())
            ->map(fn (Url $tag): string => $tag->url)
            ->values()
            ->all();

        if ($this->option('dry-run')) {
            $this->line(implode(PHP_EOL, $urls));
            $this->info(count($urls).' addresses (nothing sent).');

            return self::SUCCESS;
        }

        if (! config('indexnow.enabled')) {
            $this->error('IndexNow is switched off here (INDEXNOW_ENABLED); use --dry-run to only list the addresses.');

            return self::FAILURE;
        }

        $this->info($indexNow->submit($urls).' addresses sent to IndexNow.');

        return self::SUCCESS;
    }
}
