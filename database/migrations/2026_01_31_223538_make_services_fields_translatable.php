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
        // First, get all existing service records
        $services = DB::table('services')->get();

        // Convert columns to JSON
        Schema::table('services', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
            $table->json('points')->nullable()->change();
        });

        // Migrate existing data to translatable format (existing data is German)
        foreach ($services as $service) {
            // Prepare name translation
            $nameData = ['en' => '', 'de' => $service->name ?? ''];

            // Prepare description translation
            $descriptionData = ['en' => '', 'de' => $service->description ?? ''];

            // Handle points - existing points are in German
            $pointsData = null;
            if ($service->points) {
                $existingPoints = json_decode($service->points, true);
                if (is_array($existingPoints)) {
                    $pointsData = [
                        'de' => $existingPoints,
                        'en' => [],
                    ];
                }
            }

            // Handle links - existing documents are German
            $linksData = null;
            if ($service->links) {
                $existingLinks = json_decode($service->links, true);
                if (is_array($existingLinks)) {
                    // Add language key to each link (existing are German)
                    $linksData = array_map(function ($link) {
                        $link['lang'] = 'de';

                        return $link;
                    }, $existingLinks);
                }
            }

            DB::table('services')
                ->where('id', $service->id)
                ->update([
                    'name' => json_encode($nameData),
                    'description' => json_encode($descriptionData),
                    'points' => $pointsData ? json_encode($pointsData) : null,
                    'links' => $linksData ? json_encode($linksData) : null,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all service records
        $services = DB::table('services')->get();

        // Convert back to string/text columns
        Schema::table('services', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('description')->nullable()->change();
        });

        // Extract German values as default
        foreach ($services as $service) {
            $name = json_decode($service->name, true);
            $description = json_decode($service->description, true);
            $points = json_decode($service->points, true);
            $links = json_decode($service->links, true);

            // Extract German points
            $pointsData = null;
            if (is_array($points) && isset($points['de'])) {
                $pointsData = $points['de'];
            }

            // Remove lang key from links
            $linksData = null;
            if (is_array($links)) {
                $linksData = array_map(function ($link) {
                    unset($link['lang']);

                    return $link;
                }, $links);
            }

            DB::table('services')
                ->where('id', $service->id)
                ->update([
                    'name' => $name['de'] ?? '',
                    'description' => $description['de'] ?? '',
                    'points' => $pointsData ? json_encode($pointsData) : null,
                    'links' => $linksData ? json_encode($linksData) : null,
                ]);
        }
    }
};
