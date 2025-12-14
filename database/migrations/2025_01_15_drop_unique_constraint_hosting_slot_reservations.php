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
            if (Schema::hasTable('hosting_slot_reservations')) {
                $indexName = 'hosting_slot_reservations_lawyer_id_hosting_time_id_unique';
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('hosting_slot_reservations');
                if (isset($indexes[$indexName])) {
                    $table->dropUnique(['lawyer_id', 'hosting_time_id']);
                }
            }
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
