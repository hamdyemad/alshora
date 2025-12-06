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
        Schema::create('lawyer_office_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('lawyers')->onDelete('cascade');
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->enum('period', ['morning', 'evening']);
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
            $table->boolean('is_available')->default(false);
            $table->timestamps();

            // Unique constraint to prevent duplicate entries
            $table->unique(['lawyer_id', 'day', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer_office_hours');
    }
};
