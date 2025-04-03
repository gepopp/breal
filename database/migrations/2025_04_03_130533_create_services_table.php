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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('name')->nullable();
            $table->json('points')->nullable();
            $table->string('company')->constrained( \App\Enums\CompaniesEnum::class )->nullable();
            $table->boolean('on_landing')->default(false);
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->json('links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
