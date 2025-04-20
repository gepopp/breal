<?php

namespace Database\Seeders;

use App\Models\Timeline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('timelines')->truncate();

        $timelines = Timeline::factory()->count(20)->create();

        foreach ($timelines as $timeline) {
            $timeline->addMediaFromUrl(asset('modern-villa.jpg'))
                ->withResponsiveImages()
                ->toMediaCollection('titleimage');
        }
    }
}
