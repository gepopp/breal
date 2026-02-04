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
        // First, get all existing timeline records
        $timelines = DB::table('timelines')->get();

        // Convert columns to JSON
        Schema::table('timelines', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('description')->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($timelines as $timeline) {
            DB::table('timelines')
                ->where('id', $timeline->id)
                ->update([
                    'title' => json_encode(['en' => '', 'de' => $timeline->title]),
                    'description' => json_encode(['en' => '', 'de' => $timeline->description]),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all timeline records
        $timelines = DB::table('timelines')->get();

        // Convert back to string/text columns
        Schema::table('timelines', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('description')->change();
        });

        // Extract German values as default
        foreach ($timelines as $timeline) {
            $title = json_decode($timeline->title, true);
            $description = json_decode($timeline->description, true);

            DB::table('timelines')
                ->where('id', $timeline->id)
                ->update([
                    'title' => $title['de'] ?? '',
                    'description' => $description['de'] ?? '',
                ]);
        }
    }
};
