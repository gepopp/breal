<?php

namespace App\Services;

use App\Jobs\SubmitToIndexNow;
use App\Models\Competence;
use App\Models\Contactperson;
use App\Models\Department;
use App\Models\JobVacancy;
use App\Models\Question;
use App\Models\Realty;
use App\Models\Reference;
use App\Models\Service;
use App\Models\ServiceCard;
use App\Models\Timeline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelSettings\Settings;
use Throwable;

/**
 * Collects the addresses of pages whose content changed in the CMS and has them reported to IndexNow in one request
 * a little later (the property import changes hundreds of records in a row). The addresses wait in the cache, so
 * this works the same in a web request, a console command and the queue worker. It must never get in the way of
 * saving: every failure here is reported to the log and swallowed.
 */
class IndexNowNotifier
{
    /**
     * Models whose records are shown on public pages.
     *
     * @var list<class-string<Model>>
     */
    public const MODELS = [
        Competence::class,
        Contactperson::class,
        Department::class,
        JobVacancy::class,
        Question::class,
        Realty::class,
        Reference::class,
        Service::class,
        ServiceCard::class,
        Timeline::class,
    ];

    private const PENDING_KEY = 'indexnow:pending';

    private const SCHEDULED_KEY = 'indexnow:scheduled';

    private const LOCK_KEY = 'indexnow:lock';

    public function enabled(): bool
    {
        return (bool) config('indexnow.enabled');
    }

    /**
     * A record was saved, deleted or restored: its own page (a deleted one too, so that the engine sees the 404 or
     * 410) and the static pages that list it.
     */
    public function modelChanged(Model $model): void
    {
        if (! $this->enabled()) {
            return;
        }

        try {
            $this->queue([...SitemapBuilder::pageUrlsForModel($model::class), ...$this->detailUrls($model)]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Settings of a page group were saved: the pages that show them.
     */
    public function settingsSaved(Settings $settings): void
    {
        if (! $this->enabled()) {
            return;
        }

        try {
            $this->queue(SitemapBuilder::pageUrlsForGroup($settings::group()));
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * @param  list<string>  $urls
     */
    public function queue(array $urls): void
    {
        if ($urls === []) {
            return;
        }

        try {
            Cache::lock(self::LOCK_KEY, 5)->block(3, function () use ($urls): void {
                Cache::put(self::PENDING_KEY, array_values(array_unique([...Cache::get(self::PENDING_KEY, []), ...$urls])), now()->addDay());

                $delay = (int) config('indexnow.delay');

                // One job for all changes of the next minute; the flag expires, so a lost job does not block later ones.
                if (Cache::add(self::SCHEDULED_KEY, true, now()->addSeconds($delay + 300))) {
                    SubmitToIndexNow::dispatch()->delay(now()->addSeconds($delay));
                }
            });
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Hands over everything collected so far and starts collecting anew.
     *
     * @return list<string>
     */
    public function takePending(): array
    {
        return Cache::lock(self::LOCK_KEY, 5)->block(3, function (): array {
            Cache::forget(self::SCHEDULED_KEY);

            return array_values(Cache::pull(self::PENDING_KEY, []));
        });
    }

    /**
     * Puts addresses back that could not be sent, for the next attempt.
     *
     * @param  list<string>  $urls
     */
    public function restore(array $urls): void
    {
        Cache::lock(self::LOCK_KEY, 5)->block(3, function () use ($urls): void {
            Cache::put(self::PENDING_KEY, array_values(array_unique([...$urls, ...Cache::get(self::PENDING_KEY, [])])), now()->addDay());
        });
    }

    /**
     * The page of the record itself.
     *
     * @return list<string>
     */
    private function detailUrls(Model $model): array
    {
        try {
            return match (true) {
                $model instanceof Competence => SitemapBuilder::withEnglishUrl(route(SitemapBuilder::competenceRoute($model), $model)),
                $model instanceof Service => [$model->link],
                $model instanceof Question => [route('faq.single', $model->slug)],
                $model instanceof JobVacancy => [route('stellenanzeige', $model)],
                $model instanceof Realty => [route('makler.immobilie', $model)],
                default => [],
            };
        } catch (Throwable) {
            // A record without a complete address (no slug yet) has no page of its own to report.
            return [];
        }
    }
}
