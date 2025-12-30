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
        Schema::create('client_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('client_name'); // اسم الموكل
            $table->string('client_phone')->nullable(); // رقم التليفون
            $table->text('client_inquiry')->nullable(); // استفسار الموكل
            $table->text('follow_up_response')->nullable(); // رد مركز المتابعة
            $table->date('follow_up_date'); // تاريخ المتابعة
            $table->integer('notification_days')->default(0); // ميعاد التنبيه
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_agendas');
    }
};
