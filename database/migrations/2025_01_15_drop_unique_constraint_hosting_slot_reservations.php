<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexExists = DB::select("SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_NAME = 'hosting_slot_reservations' AND INDEX_NAME = 'hosting_slot_reservations_lawyer_id_hosting_time_id_unique' LIMIT 1");

        if (!empty($indexExists)) {
            Schema::table('hosting_slot_reservations', function (Blueprint $table) {
                $table->dropUnique(['lawyer_id', 'hosting_time_id']);
            });
        }
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
