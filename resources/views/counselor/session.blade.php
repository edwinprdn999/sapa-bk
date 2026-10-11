@extends('layouts.app')

@section('title', 'Pelaksanaan Bimbingan & Catatan Rahasia')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('counselor.appointment.detail', $appointment->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">
                    Lembar Catatan Bimbingan Siswa
                </h1>
                <p class="text-xs text-slate-500">
                    Siswa: {{ $appointment->student->user->name ?? '-' }} (NISN: {{ $appointment->student->student_number ?? '-' }}, {{ $appointment->student->study_program ?? '' }}) &bull; {{ $appointment->appointment_code }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold">
            <span class="material-symbols-outlined text-sm">lock</span>
            Sangat Rahasia
        </div>
    </div>

    <!-- Session Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm">
        <form action="{{ route('counselor.session.update', $session->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Pelaksanaan & Kehadiran -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Status Kehadiran Siswa <span class="text-rose-500">*</span></label>
                    <select name="attendance_status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-medium focus:ring-2 focus:ring-teal-500/20">
                        <option value="attended" {{ $session->attendance_status === 'attended' ? 'selected' : '' }}>Siswa Hadir di Ruang BK</option>
                        <option value="no_show" {{ $session->attendance_status === 'no_show' ? 'selected' : '' }}>Tidak Hadir (No-Show)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Waktu Mulai Bimbingan</label>
                    <input type="datetime-local" name="started_at"
                        value="{{ $session->started_at ? $session->started_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-teal-500/20">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Waktu Selesai Bimbingan</label>
                    <input type="datetime-local" name="ended_at"
                        value="{{ $session->ended_at ? $session->ended_at->format('Y-m-d\TH:i') : now()->addMinutes(45)->format('Y-m-d\TH:i') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-teal-500/20">
                </div>
            </div>

            <!-- 2. CATATAN RAHASIA INTERNAL GURU BK (Confidential Note) -->
            <div class="pt-2">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined text-rose-600 text-lg">shield</span>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-900">
                        Catatan Profesional Guru BK (Terkunci & Rahasia) <span class="text-rose-500">*</span>
                    </label>
                </div>
                <p class="text-xs text-slate-500 mb-2">
                    Catatan psikologis dan bimbingan internal ini disimpan terpisah di tabel terlindungi. <strong>Siswa dan Administrator Sekolah TIDAK dapat membaca bagian ini.</strong>
                </p>
                <textarea name="confidential_note" rows="5" required minlength="10" placeholder="Tuliskan latar belakang masalah siswa, observasi perilaku di sekolah, faktor penyebab (keluarga/teman/belajar), serta intervensi bimbingan yang telah diberikan..."
                    class="w-full p-4 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 bg-white">{{ old('confidential_note', $session->confidentialNote->confidential_note ?? '') }}</textarea>
            </div>

            <!-- 3. Evaluasi Perkembangan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Evaluasi Perkembangan Siswa / Rekomendasi
                </label>
                <textarea name="evaluation_summary" rows="2" placeholder="Contoh: Siswa kooperatif, menunjukkan kemauan memperbaiki nilai, disarankan koordinasi dengan wali kelas jika diperlukan..."
                    class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">{{ old('evaluation_summary', $session->confidentialNote->evaluation_summary ?? '') }}</textarea>
            </div>

            <!-- 4. Ringkasan yang Dapat Dibaca Siswa (Public Summary) -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Catatan Motivasi & Refleksi untuk Siswa (Dapat Dibaca Siswa di Akunnya)
                </label>
                <p class="text-xs text-slate-500 mb-2">
                    Pesan suportif dan rangkuman kesepakatan belajar yang aman untuk dibaca oleh siswa.
                </p>
                <textarea name="public_summary" rows="2" placeholder="Contoh: Terima kasih sudah hadir dan terbuka di Ruang BK hari ini. Terus semangat, jangan sungkan kembali jika ada hal yang ingin diceritakan..."
                    class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">{{ old('public_summary', $session->public_summary ?? '') }}</textarea>
            </div>

            <!-- 5. Buat Rencana Tindak Lanjut (Follow-Up) -->
            <div class="pt-4 border-t border-slate-100 p-5 rounded-2xl bg-teal-50/40 border border-teal-100 space-y-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="create_follow_up" name="create_follow_up" value="1" class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500">
                    <label for="create_follow_up" class="text-xs font-bold text-teal-900 cursor-pointer">
                        Tetapkan Rencana Tindak Lanjut / Tugas Mandiri (Action Plan) untuk Siswa
                    </label>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-teal-800 mb-1">Deskripsi Tugas / Target Aksi Siswa</label>
                        <input type="text" name="action_plan" placeholder="Contoh: Menyusun jadwal belajar harian 45 menit dan konsultasi perkembangan nilai minggu depan"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-teal-200 bg-white text-xs focus:ring-2 focus:ring-teal-500/20">
                    </div>

                    <div class="w-full sm:w-1/2">
                        <label class="block text-xs font-semibold text-teal-800 mb-1">Tenggat Waktu Pelaksanaan</label>
                        <input type="date" name="due_date" min="{{ date('Y-m-d') }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-teal-200 bg-white text-xs focus:ring-2 focus:ring-teal-500/20">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('counselor.appointment.detail', $appointment->id) }}" class="px-5 py-2.5 rounded-full border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors">
                    Kembali
                </a>
                <button type="submit" class="px-7 py-2.5 rounded-full bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold shadow-md shadow-teal-600/20 transition-all cursor-pointer">
                    Simpan Sesi & Catatan Rahasia Guru BK
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
