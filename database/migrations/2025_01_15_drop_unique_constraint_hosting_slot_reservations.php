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
        Schema::table('hosting_slot_reservations', function (Blueprint $table) {
            $table->dropUnique(['lawyer_id', 'hosting_time_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hosting_slot_reservations', function (Blueprint $table) {
            $table->unique(['lawyer_id', 'hosting_time_id']);
        });
    }
};
