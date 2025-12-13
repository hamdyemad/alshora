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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('lawyers')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->integer('rating')->min(1)->max(5);
            $table->text('comment')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('lawyer_id');
            $table->index('customer_id');
            $table->index('approved');
            $table->unique(['lawyer_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
