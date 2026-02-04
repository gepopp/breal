<?php

namespace App\Console\Commands;

use App\Settings\HausverwaltungLandingpageSettings;
use App\Settings\LandingpageHausverwaltung;
use App\Settings\LandingpageMaklerSettings as OldMaklerSettings;
use App\Settings\LandingpageTechnikSettings;
use App\Settings\MaklerLandingpageSettings;
use App\Settings\TechnikLandingpageSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyLandingpageSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:copy-landingpage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy image IDs and layout settings from old landingpage settings to new translatable settings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Copying landingpage settings...');

        // Copy Hausverwaltung settings
        $this->copyHausverwaltungSettings();

        // Copy Makler settings
        $this->copyMaklerSettings();

        // Copy Technik settings
        $this->copyTechnikSettings();

        $this->info('✓ All landingpage settings copied successfully!');

        return Command::SUCCESS;
    }

    private function copyHausverwaltungSettings(): void
    {
        $this->line('Copying Hausverwaltung settings...');

        $old = app(LandingpageHausverwaltung::class);

        // Update settings directly in database
        $this->updateSetting('translatable_hausverwaltung_landingpage', 'hero_image', $old->hero_image);
        $this->updateSetting('translatable_hausverwaltung_landingpage', 'text_image', $old->text_image);
        $this->updateSetting('translatable_hausverwaltung_landingpage', 'about_image', $old->about_image);
        $this->updateSetting('translatable_hausverwaltung_landingpage', 'intro_layout', $old->intro_layout);
        $this->updateSetting('translatable_hausverwaltung_landingpage', 'hero_speed', $old->hero_speed);
        $this->updateSetting('translatable_hausverwaltung_landingpage', 'timeline_speed', $old->timeline_speed);
        $this->updateSetting('translatable_hausverwaltung_landingpage', 'about_video_embed_code', $old->about_video_embed_code);

        $this->info('  ✓ Hausverwaltung: hero_image, text_image, about_image, intro_layout, speeds, video');
    }

    private function copyMaklerSettings(): void
    {
        $this->line('Copying Makler settings...');

        $old = app(OldMaklerSettings::class);

        // Update settings directly in database
        $this->updateSetting('translatable_makler', 'hero_images', $old->hero_images);
        $this->updateSetting('translatable_makler', 'cta_image', $old->cta_image);
        $this->updateSetting('translatable_makler', 'about_image', $old->about_image);
        $this->updateSetting('translatable_makler', 'cta_video_embed_code', $old->cta_video_embed_code);

        $this->info('  ✓ Makler: hero_images, cta_image, about_image, video');
    }

    private function copyTechnikSettings(): void
    {
        $this->line('Copying Technik settings...');

        $old = app(LandingpageTechnikSettings::class);

        // Update settings directly in database
        $this->updateSetting('translatable_technik', 'hero_image', $old->hero_image);
        $this->updateSetting('translatable_technik', 'text_image', $old->text_image);
        $this->updateSetting('translatable_technik', 'about_image', $old->about_image);
        $this->updateSetting('translatable_technik', 'intro_layout', $old->intro_layout);
        $this->updateSetting('translatable_technik', 'about_video_embed_code', $old->about_video_embed_code);

        $this->info('  ✓ Technik: hero_image, text_image, about_image, intro_layout, video');
    }

    private function updateSetting(string $group, string $name, mixed $value): void
    {
        DB::table('settings')
            ->where('group', $group)
            ->where('name', $name)
            ->update([
                'payload' => json_encode($value),
            ]);
    }
}
