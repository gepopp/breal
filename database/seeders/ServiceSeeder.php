<?php

namespace Database\Seeders;

use App\Models\Service;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->truncate();

        $services = Service::factory()->count(20)->create();

        foreach ($services as $service) {
            $document = Http::get(asset('Sample_PDF.pdf'));
            $path = '/documents/' . $service->slug . '/' . Str::random() . '.pdf';
            Storage::disk('public')->put($path, $document->getBody(), 'public');

            $service->update([
                'links' => [
                    [
                        'name' => fake()->sentence(),
                        'path' => $path,
                        'url'  => Storage::url($path),
                    ],
                ],
                'list'  => [
                    [
                        'icon' => fake()->randomElement(['none', 'rufzeichen', 'stern', 'arrow']),
                        'list' => [
                            fake()->sentence(),
                            fake()->sentence(),
                            fake()->sentence(),
                        ]
                    ]
                ]
            ]);


        }
    }
}
