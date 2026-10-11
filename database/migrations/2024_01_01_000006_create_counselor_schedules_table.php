<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->constrained('counselors')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_booked')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Prevent duplicate schedule slot for same counselor
            $table->unique(['counselor_id', 'date', 'start_time'], 'uq_counselor_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselor_schedules');
    }
};
