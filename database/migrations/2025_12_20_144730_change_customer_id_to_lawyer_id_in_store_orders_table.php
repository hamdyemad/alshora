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
        Schema::table('store_orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->renameColumn('customer_id', 'lawyer_id');
            $table->foreign('lawyer_id')->references('id')->on('lawyers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->dropForeign(['lawyer_id']);
            $table->renameColumn('lawyer_id', 'customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }
};
