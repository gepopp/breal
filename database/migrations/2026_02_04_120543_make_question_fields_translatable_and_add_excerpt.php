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
        // First, get all existing question records
        $questions = DB::table('questions')->get();

        // Add excerpt column if it doesn't exist and convert columns to JSON
        Schema::table('questions', function (Blueprint $table) {
            if (! Schema::hasColumn('questions', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('question');
            }
            $table->json('question')->change();
            $table->json('excerpt')->nullable()->change();
            $table->json('answer')->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($questions as $question) {
            DB::table('questions')
                ->where('id', $question->id)
                ->update([
                    'question' => json_encode(['en' => '', 'de' => $question->question]),
                    'excerpt' => json_encode(['en' => '', 'de' => $question->excerpt ?? '']),
                    'answer' => json_encode(['en' => '', 'de' => $question->answer]),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all question records
        $questions = DB::table('questions')->get();

        // Convert back to string/text columns and remove excerpt
        Schema::table('questions', function (Blueprint $table) {
            $table->string('question')->change();
            $table->text('answer')->change();
            $table->dropColumn('excerpt');
        });

        // Extract German values as default
        foreach ($questions as $question) {
            $questionText = json_decode($question->question, true);
            $answer = json_decode($question->answer, true);

            DB::table('questions')
                ->where('id', $question->id)
                ->update([
                    'question' => $questionText['de'] ?? '',
                    'answer' => $answer['de'] ?? '',
                ]);
        }
    }
};
