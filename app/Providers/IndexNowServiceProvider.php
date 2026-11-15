<?php

namespace App\Providers;

use App\Services\IndexNowNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelSettings\Events\SettingsSaved;

/**
 * Reports changes made in the CMS to IndexNow (see IndexNowNotifier).
 */
class IndexNowServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        foreach (IndexNowNotifier::MODELS as $class) {
            // Saving a record that did not change anything (the property import saves every record) reports nothing.
            Event::listen("eloquent.saved: {$class}", function (Model $model): void {
                if ($model->wasRecentlyCreated || $model->wasChanged()) {
                    app(IndexNowNotifier::class)->modelChanged($model);
                }
            });

            Event::listen(["eloquent.deleted: {$class}", "eloquent.restored: {$class}"], function (Model $model): void {
                app(IndexNowNotifier::class)->modelChanged($model);
            });
        }

        Event::listen(SettingsSaved::class, function (SettingsSaved $event): void {
            app(IndexNowNotifier::class)->settingsSaved($event->settings);
        });
    }
}
