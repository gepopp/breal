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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('company');
            $table->string('title');
            $table->string('job_title')->nullable();
            $table->string('contract_type')->nullable();
            $table->longText('description');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->string('email');
            $table->string('unit_text')->nullable();
            $table->decimal('salary', 8, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
