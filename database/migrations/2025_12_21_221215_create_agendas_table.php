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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action_number');
            $table->string('years');
            $table->string('action_subject');
            $table->string('court');
            $table->string('district_number');
            $table->text('details')->nullable();
            $table->string('claiment_name');
            $table->string('defendant_name');
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
        Schema::dropIfExists('agendas');
    }
};
