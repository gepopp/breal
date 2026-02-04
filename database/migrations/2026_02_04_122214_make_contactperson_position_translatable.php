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
        // First, get all existing contactperson records
        $contactpeople = DB::table('contactpeople')->get();

        // Convert position column to JSON
        Schema::table('contactpeople', function (Blueprint $table) {
            $table->json('position')->nullable()->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($contactpeople as $contact) {
            DB::table('contactpeople')
                ->where('id', $contact->id)
                ->update([
                    'position' => $contact->position
                        ? json_encode(['en' => '', 'de' => $contact->position])
                        : null,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all contactperson records
        $contactpeople = DB::table('contactpeople')->get();

        // Convert back to string column
        Schema::table('contactpeople', function (Blueprint $table) {
            $table->string('position')->nullable()->change();
        });

        // Extract German values as default
        foreach ($contactpeople as $contact) {
            $position = $contact->position ? json_decode($contact->position, true) : null;

            DB::table('contactpeople')
                ->where('id', $contact->id)
                ->update([
                    'position' => $position['de'] ?? null,
                ]);
        }
    }
};
