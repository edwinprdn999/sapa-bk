<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // TABEL KHUSUS RAHASIA: Terpisah dari appointments dan notifikasi umum
        Schema::create('counseling_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->unique()->constrained('counseling_sessions')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->longText('confidential_note'); // Catatan profesional/psikologis rahasia
            $table->text('evaluation_summary')->nullable(); // Evaluasi perkembangan
            $table->boolean('is_encrypted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_notes');
    }
};
