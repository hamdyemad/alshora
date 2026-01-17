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
        // Add 'completed' and 'cancelled' back to the enum
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing records before removing enum values
        DB::table('appointments')
            ->where('status', 'completed')
            ->update(['status' => 'approved']);
        
        DB::table('appointments')
            ->where('status', 'cancelled')
            ->update(['status' => 'rejected']);

        // Revert to previous enum
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
