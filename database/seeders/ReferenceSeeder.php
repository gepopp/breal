<?php

namespace Database\Seeders;

use App\Models\Reference;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('references')->truncate();

        $references = Reference::factory()->count(10)->create();

        foreach ($references as $reference) {
            $reference->addMediaFromUrl(asset('modern-villa.jpg'))
                ->withResponsiveImages()
                ->toMediaCollection('titleimage');
            $reference->addMediaFromUrl(asset('headschot.jpg'))
                ->withResponsiveImages()
                ->toMediaCollection('avatar');
        }
    }
}
