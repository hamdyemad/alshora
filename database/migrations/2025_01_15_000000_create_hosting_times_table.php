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
        Schema::create('hosting_times', function (Blueprint $table) {
            $table->id();
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint to prevent duplicate entries for the same day
            $table->unique(['day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_times');
    }
};
