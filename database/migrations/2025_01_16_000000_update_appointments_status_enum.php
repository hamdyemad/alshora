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
        // Update existing records
        DB::table('appointments')
            ->where('status', 'confirmed')
            ->update(['status' => 'approved']);
        
        DB::table('appointments')
            ->where('status', 'completed')
            ->update(['status' => 'approved']);
        
        DB::table('appointments')
            ->where('status', 'cancelled')
            ->update(['status' => 'rejected']);

        // Modify the enum column
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old enum values
        DB::table('appointments')
            ->where('status', 'approved')
            ->update(['status' => 'confirmed']);
        
        DB::table('appointments')
            ->where('status', 'rejected')
            ->update(['status' => 'cancelled']);

        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending'");
    }
};
