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
        Schema::table('hosting_times', function (Blueprint $table) {
            // Drop the unique constraint on day
            $table->dropUnique(['day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hosting_times', function (Blueprint $table) {
            $table->unique(['day']);
        });
    }
};
