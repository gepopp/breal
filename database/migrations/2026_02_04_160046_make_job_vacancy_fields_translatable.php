<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, get all existing job vacancy records
        $jobVacancies = DB::table('job_vacancies')->get();

        // Convert columns to JSON
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('job_title')->change();
            $table->json('description')->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($jobVacancies as $jobVacancy) {
            DB::table('job_vacancies')
                ->where('id', $jobVacancy->id)
                ->update([
                    'title' => json_encode(['en' => '', 'de' => $jobVacancy->title]),
                    'job_title' => json_encode(['en' => '', 'de' => $jobVacancy->job_title]),
                    'description' => json_encode(['en' => '', 'de' => $jobVacancy->description]),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all job vacancy records
        $jobVacancies = DB::table('job_vacancies')->get();

        // Convert back to string/text columns
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('title')->change();
            $table->string('job_title')->change();
            $table->text('description')->change();
        });

        // Extract German values as default
        foreach ($jobVacancies as $jobVacancy) {
            $title = json_decode($jobVacancy->title, true);
            $jobTitle = json_decode($jobVacancy->job_title, true);
            $description = json_decode($jobVacancy->description, true);

            DB::table('job_vacancies')
                ->where('id', $jobVacancy->id)
                ->update([
                    'title' => $title['de'] ?? '',
                    'job_title' => $jobTitle['de'] ?? '',
                    'description' => $description['de'] ?? '',
                ]);
        }
    }
};
