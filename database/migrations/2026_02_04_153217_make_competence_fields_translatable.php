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
        // First, get all existing competence records
        $competences = DB::table('competences')->get();

        // Convert columns to JSON
        Schema::table('competences', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
            $table->json('body')->nullable()->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($competences as $competence) {
            DB::table('competences')
                ->where('id', $competence->id)
                ->update([
                    'name' => json_encode(['en' => '', 'de' => $competence->name]),
                    'description' => json_encode(['en' => '', 'de' => $competence->description]),
                    'body' => $competence->body
                        ? json_encode(['en' => '', 'de' => $competence->body])
                        : null,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all competence records
        $competences = DB::table('competences')->get();

        // Convert back to string/text columns
        Schema::table('competences', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
            $table->text('body')->nullable()->change();
        });

        // Extract German values as default
        foreach ($competences as $competence) {
            $name = json_decode($competence->name, true);
            $description = json_decode($competence->description, true);
            $body = $competence->body ? json_decode($competence->body, true) : null;

            DB::table('competences')
                ->where('id', $competence->id)
                ->update([
                    'name' => $name['de'] ?? '',
                    'description' => $description['de'] ?? '',
                    'body' => $body['de'] ?? null,
                ]);
        }
    }
};
