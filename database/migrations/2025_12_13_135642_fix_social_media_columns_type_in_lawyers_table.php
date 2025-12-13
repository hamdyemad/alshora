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
        Schema::table('lawyers', function (Blueprint $table) {
            // Change column types from integer to string
            $table->string('facebook_url', 500)->nullable()->change();
            $table->string('twitter_url', 500)->nullable()->change();
            $table->string('instagram_url', 500)->nullable()->change();
            $table->string('telegram_url', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lawyers', function (Blueprint $table) {
            // Revert back to integer (though this is not recommended)
            $table->unsignedBigInteger('facebook_url')->nullable()->change();
            $table->unsignedBigInteger('twitter_url')->nullable()->change();
            $table->unsignedBigInteger('instagram_url')->nullable()->change();
            $table->unsignedBigInteger('telegram_url')->nullable()->change();
        });
    }
};
