<?php

namespace Database\Seeders;

use App\Models\Competence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('competences')->truncate();

        $competences = Competence::factory()->count(20)->create();

        $images = glob(public_path('/homes') . '/*');

        foreach ($competences as $competence) {

            $image_path = $images[array_rand($images)];
            $info = pathinfo($image_path);
            $url = asset('homes/' . $info['basename']);

            $competence->addMediaFromUrl($url)->toMediaCollection('titleimage')->responsiveImages();
        }

    }
}
