@extends('layouts.app')

@section('title', 'Tinjau Permohonan ' . $appointment->appointment_code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('counselor.appointments') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">
                    Permohonan Bimbingan {{ $appointment->appointment_code }}
                </h1>
                <p class="text-xs text-slate-500">Diajukan pada {{ $appointment->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $appointment->status_color_class }}">
            {{ $appointment->status_label }}
        </span>
    </div>

    <!-- Info Siswa & Jadwal -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-5 rounded-2xl bg-slate-50 border border-slate-100">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Identitas Siswa</span>
                <div class="font-bold text-slate-900 text-sm">{{ $appointment->student->user->name ?? '-' }}</div>
                <div class="text-xs text-slate-600 mt-0.5">
                    NISN: <span class="font-semibold text-slate-800">{{ $appointment->student->student_number ?? '-' }}</span> &bull;
                    Kelas: <span class="font-semibold text-slate-800">{{ $appointment->student->study_program ?? '-' }}</span>
                </div>
                <div class="text-xs text-slate-500 mt-1">
                    Peminatan: {{ $appointment->student->faculty ?? '-' }} | Kontak Orang Tua: {{ $appointment->student->emergency_contact ?? '-' }}
                </div>
            </div>

            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Waktu Permintaan</span>
                <div class="font-bold text-slate-900 text-sm">
                    {{ \Carbon\Carbon::parse($appointment->consultation_date)->translatedFormat('l, d F Y') }}
                </div>
                <div class="text-xs text-slate-600 mt-0.5">
                    Pukul {{ substr($appointment->start_time, 0, 5) }} - {{ substr($appointment->end_time, 0, 5) }} WIB
                </div>
                <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-xs font-medium text-slate-700">
                    <span class="material-symbols-outlined text-sm text-teal-600">
                        {{ $appointment->method === 'online' ? 'videocam' : 'meeting_room' }}
                    </span>
                    {{ $appointment->method === 'online' ? 'Bimbingan Daring (Online)' : 'Tatap Muka di Ruang BK Sekolah' }}
                </div>
            </div>
        </div>

        <!-- Deskripsi Keluhan Siswa -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Cerita / Kebutuhan Siswa</h3>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-800 leading-relaxed whitespace-pre-line font-medium">
                {{ $appointment->initial_concern }}
            </div>
        </div>

        <!-- Ruang / Lokasi & Tanggapan Saat Ini -->
        @if($appointment->counselor_response_reason)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 text-xs text-amber-900">
                <span class="font-bold block mb-1">Tanggapan/Catatan Guru BK:</span>
                {{ $appointment->counselor_response_reason }}
            </div>
        @endif

        @if($appointment->meeting_link_or_room)
            <div class="p-4 rounded-2xl bg-teal-50 border border-teal-100 text-xs text-teal-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-teal-700 text-base">place</span>
                <span class="font-bold">Ruang / Tautan Pertemuan:</span> {{ $appointment->meeting_link_or_room }}
            </div>
        @endif

        <!-- Sesi & Catatan Terproteksi (Jika Sudah Disetujui/Selesai) -->
        @if(in_array($appointment->status, ['approved', 'in_progress', 'completed']))
            <div class="p-6 rounded-2xl bg-teal-50 border border-teal-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h4 class="font-bold text-teal-900 text-sm">Pelaksanaan Bimbingan & Catatan Rahasia Guru BK</h4>
                    <p class="text-xs text-teal-700 mt-0.5">Catat absensi kehadiran siswa, refleksi profesional, dan rencana tindak lanjut.</p>
                </div>
                <a href="{{ route('counselor.session.view', $appointment->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-base">clinical_notes</span>
                    Buka Lembar Sesi Bimbingan
                </a>
            </div>
        @endif

        <!-- Formulir Disposisi Permohonan (Jika Masih Pending / Reschedule) -->
        @if(in_array($appointment->status, ['pending', 'reschedule_requested']))
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-bold text-slate-900">Tentukan Keputusan Guru BK</h3>

                <form action="{{ route('counselor.appointment.status', $appointment->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilihan Tindakan</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50 text-xs font-semibold">
                                <input type="radio" name="action" value="approve" required checked class="text-emerald-600">
                                <span>Setujui (Approve)</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-50 text-xs font-semibold">
                                <input type="radio" name="action" value="reschedule" required class="text-amber-600">
                                <span>Minta Reschedule</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-rose-500 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50 text-xs font-semibold">
                                <input type="radio" name="action" value="reject" required class="text-rose-600">
                                <span>Tolak Permohonan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Ruang Temu atau Tautan Pertemuan (Jika Disetujui)
                        </label>
                        <input type="text" name="meeting_link_or_room"
                            value="{{ old('meeting_link_or_room', $appointment->method === 'online' ? 'https://meet.google.com/' : 'Ruang BK Gedung Utama Lt. 1') }}"
                            placeholder="Contoh: Ruang BK Gedung Utama atau Ruang BK Sayap Barat"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Alasan / Catatan Konfirmasi untuk Siswa (Wajib jika Menolak atau Reschedule)
                        </label>
                        <textarea name="reason" rows="3" placeholder="Tuliskan catatan arahan, lokasi khusus, atau alasan jika jadwal perlu diubah..."
                            class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">{{ old('reason') }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-full bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                        Simpan Keputusan & Beri Notifikasi
                    </button>
                </form>
            </div>
        @endif

    </div>

</div>
@endsection
