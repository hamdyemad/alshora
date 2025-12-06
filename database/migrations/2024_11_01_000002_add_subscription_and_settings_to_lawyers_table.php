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
            $table->foreignId('subscription_id')->nullable()->after('active')->constrained('subscriptions')->onDelete('set null');
            $table->date('subscription_expires_at')->nullable()->after('subscription_id');
            $table->boolean('ads_enabled')->default(1)->after('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropColumn(['subscription_id', 'subscription_expires_at', 'ads_enabled']);
        });
    }
};
