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
        Schema::create('preparer_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('court_bailiffs');
            $table->string('paper_type');
            $table->date('paper_delivery_date');
            $table->string('paper_number');
            $table->date('session_date');
            $table->string('client_name');
            $table->text('notes')->nullable();
            $table->dateTime('datetime');
            $table->integer('notification_days')->default(0);
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparer_agendas');
    }
};
