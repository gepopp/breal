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
        // First, get all existing reference records
        $references = DB::table('references')->get();

        // Convert columns to JSON
        Schema::table('references', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('description')->nullable()->change();
            $table->json('testimonial')->nullable()->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($references as $reference) {
            DB::table('references')
                ->where('id', $reference->id)
                ->update([
                    'title' => json_encode(['en' => '', 'de' => $reference->title]),
                    'description' => $reference->description
                        ? json_encode(['en' => '', 'de' => $reference->description])
                        : null,
                    'testimonial' => $reference->testimonial
                        ? json_encode(['en' => '', 'de' => $reference->testimonial])
                        : null,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all reference records
        $references = DB::table('references')->get();

        // Convert back to string/text columns
        Schema::table('references', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('description')->nullable()->change();
            $table->text('testimonial')->nullable()->change();
        });

        // Extract German values as default
        foreach ($references as $reference) {
            $title = json_decode($reference->title, true);
            $description = $reference->description ? json_decode($reference->description, true) : null;
            $testimonial = $reference->testimonial ? json_decode($reference->testimonial, true) : null;

            DB::table('references')
                ->where('id', $reference->id)
                ->update([
                    'title' => $title['de'] ?? '',
                    'description' => $description['de'] ?? null,
                    'testimonial' => $testimonial['de'] ?? null,
                ]);
        }
    }
};
