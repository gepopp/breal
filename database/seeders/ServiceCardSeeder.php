<?php

namespace Database\Seeders;

use App\Models\ServiceCard;
use Illuminate\Database\Seeder;

class ServiceCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 6 service cards
        ServiceCard::factory()
            ->service()
            ->count(6)
            ->sequence(
                ['order' => 1],
                ['order' => 2],
                ['order' => 3],
                ['order' => 4],
                ['order' => 5],
                ['order' => 6],
            )
            ->create();

        // Create 6 feature cards
        ServiceCard::factory()
            ->feature()
            ->count(6)
            ->sequence(
                ['order' => 1],
                ['order' => 2],
                ['order' => 3],
                ['order' => 4],
                ['order' => 5],
                ['order' => 6],
            )
            ->create();
    }
}
