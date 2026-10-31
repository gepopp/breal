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
        Schema::dropIfExists('f_a_q_s');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('f_a_q_s', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->nullable();
            $table->string('question');
            $table->text('answer');
            $table->timestamps();
            $table->string('slug')->nullable();
        });
    }
};
