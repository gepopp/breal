<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TeamSettings extends Settings
{
    // Team Page
    public string $team_header_de = 'with us';

    public string $team_header_en = 'with us';

    public string $team_subheader_de = 'Das Team von be real Immobilien';

    public string $team_subheader_en = 'The Team of be real Immobilien';

    public string $team_introtext_de = '<p>Auf ein Wort: Unser gesamtes Team strengt sich enorm an, damit in Ihrem Gebäude alles reibungslos läuft. Manchmal brauchen Behebungen von Schäden, Behördenwege etc. etwas mehr Zeit. Aber Sie können sich einer Sache sicher sein: Wir lassen nicht locker, bis alles zu Ihrer Zufriendheit läuft!</p>';

    public string $team_introtext_en = '<p>A word to the wise: Our entire team works extremely hard to ensure that everything runs smoothly in your building. Sometimes repairs, administrative procedures, etc. take a little more time. But you can be sure of one thing: We won\'t give up until everything is running to your satisfaction!</p>';

    public static function group(): string
    {
        return 'translatable_team';
    }
}
