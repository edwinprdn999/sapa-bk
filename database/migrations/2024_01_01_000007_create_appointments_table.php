<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_code', 30)->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('counselors')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('counseling_services')->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('counselor_schedules')->onDelete('set null');
            $table->date('consultation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('method', ['offline', 'online'])->default('offline');
            $table->text('initial_concern'); // ringkasan topik/kebutuhan awal
            $table->enum('status', [
                'pending',
                'approved',
                'reschedule_requested',
                'rejected',
                'cancelled',
                'in_progress',
                'completed',
                'no_show'
            ])->default('pending');
            $table->text('counselor_response_reason')->nullable();
            $table->string('meeting_link_or_room', 255)->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['counselor_id', 'status']);
            $table->index(['consultation_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
