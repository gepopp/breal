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
        // First, get all existing department records
        $departments = DB::table('departments')->get();

        // Drop the virtual column first
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('search_label');
        });

        // Convert name column to JSON
        Schema::table('departments', function (Blueprint $table) {
            $table->json('name')->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($departments as $department) {
            DB::table('departments')
                ->where('id', $department->id)
                ->update([
                    'name' => json_encode(['en' => '', 'de' => $department->name]),
                ]);
        }

        // Recreate the search_label virtual column
        Schema::table('departments', function (Blueprint $table) {
            $table->string('search_label')->virtualAs('name || \' - \' || company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all department records
        $departments = DB::table('departments')->get();

        // Drop the virtual column
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('search_label');
        });

        // Convert back to string column
        Schema::table('departments', function (Blueprint $table) {
            $table->string('name')->change();
        });

        // Extract German values as default
        foreach ($departments as $department) {
            $name = json_decode($department->name, true);

            DB::table('departments')
                ->where('id', $department->id)
                ->update([
                    'name' => $name['de'] ?? '',
                ]);
        }

        // Recreate the search_label virtual column
        Schema::table('departments', function (Blueprint $table) {
            $table->string('search_label')->virtualAs('name || \' - \' || company');
        });
    }
};
