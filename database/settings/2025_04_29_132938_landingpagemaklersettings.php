<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('MaklerSettings.cta_header', 'with us');
        $this->migrator->add('MaklerSettings.cta_subheader', 'Wir vermitteln auch Ihre Immobilien');
        $this->migrator->add('MaklerSettings.cta_text', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aperiam beatae corporis debitis deserunt dignissimos, dolor dolorum eaque fugit harum incidunt necessitatibus nemo odit optio praesentium recusandae saepe unde vel!</p>');
        $this->migrator->add('MaklerSettings.cta_image', 0);
        $this->migrator->add('MaklerSettings.cta_image_alt', 'Bontus Eybel Intro Bild');
        $this->migrator->add('MaklerSettings.cta_video_embed_code', '');
    }
};
