<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('realties');

        Schema::create('realties', function (Blueprint $table) {
            $table->id();

            $table->string('slug');

            // Verwaltungstechnische Informationen
            $table->string('objektnr_intern')->nullable();
            $table->string('objektnr_extern')->nullable();
            $table->string('openimmo_obid')->nullable()->index();

            // Objektbeschreibung
            $table->string('title')->nullable();
            $table->longText('beschreibung')->nullable();

            // Objektdetails
            $table->decimal('zimmer', 5, 1)->nullable()->comment('Anzahl der Zimmer');
            $table->decimal('wohnflaeche', 10, 2)->nullable()->comment('Wohnfläche in m²');
            $table->decimal('preis', 14, 2)->nullable();
            $table->enum('vermarktungsart', ['kauf', 'miete'])->default('miete');
            $table->string('nutzungsart')->nullable();

            // Geographische Informationen
            $table->string('plz', 10)->nullable();
            $table->string('ort')->nullable();
            $table->string('strasse')->nullable();
            $table->string('hausnummer', 10)->nullable();
            $table->string('bundesland')->nullable();
            $table->string('wohnungsnummer', 20)->nullable();
            $table->decimal('lat', 10, 7)->nullable()->comment('Breitengrad');
            $table->decimal('lng', 10, 7)->nullable()->comment('Längengrad');

            $table->string('path')->nullable();

            $table->timestamps();

            // Indizes für häufige Suchkriterien
            $table->index(['vermarktungsart', 'nutzungsart']);
            $table->index(['plz', 'ort']);
            $table->index('preis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realties', function (Blueprint $table) {
            //
        });
    }
};
