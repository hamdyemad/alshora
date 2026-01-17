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
        Schema::create('lawyer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('lawyers')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->enum('type', ['income', 'expense']); // دخل أو مصروف
            $table->decimal('amount', 10, 2);
            $table->string('category')->nullable(); // consultation, subscription, office_rent, etc.
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
            
            $table->index(['lawyer_id', 'type']);
            $table->index(['lawyer_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer_transactions');
    }
};
