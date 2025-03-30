<?php

namespace Database\Seeders;

use App\Models\JobVacancy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_vacancies')->truncate();

        $jobs = JobVacancy::factory()->count(16)->create();

        foreach ($jobs as $job) {
            $job->addMediaFromDisk( public_path('job-default.jpg') );
        }
    }
}
