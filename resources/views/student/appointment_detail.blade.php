@extends('layouts.app')

@section('title', 'Detail Permohonan ' . $appointment->appointment_code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Back -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.appointments') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">
                    Bimbingan {{ $appointment->appointment_code }}
                </h1>
                <p class="text-xs text-slate-500">Diajukan pada {{ $appointment->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $appointment->status_color_class }}">
            {{ $appointment->status_label }}
        </span>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-6">

        <!-- Info Jadwal & Guru BK -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-5 rounded-2xl bg-slate-50 border border-slate-100">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Guru BK Pembimbing</span>
                <div class="font-bold text-slate-900 text-sm">{{ $appointment->counselor->user->name ?? '-' }}</div>
                <div class="text-xs text-teal-700 font-medium">{{ $appointment->counselor->specialization }}</div>
                <div class="text-[11px] text-slate-500 mt-1">NIP/NUPTK: {{ $appointment->counselor->employee_number }}</div>
            </div>

            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Jadwal & Lokasi</span>
                <div class="font-bold text-slate-900 text-sm">
                    {{ \Carbon\Carbon::parse($appointment->consultation_date)->translatedFormat('l, d F Y') }}
                </div>
                <div class="text-xs text-slate-600 mt-0.5">
                    Pukul {{ substr($appointment->start_time, 0, 5) }} - {{ substr($appointment->end_time, 0, 5) }} WIB
                </div>
                <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-xs font-medium text-slate-700">
                    <span class="material-symbols-outlined text-sm text-primary-600">
                        {{ $appointment->method === 'online' ? 'videocam' : 'meeting_room' }}
                    </span>
                    {{ $appointment->method === 'online' ? 'Bimbingan Daring (Online)' : 'Tatap Muka di Ruang BK Sekolah' }}
                </div>
            </div>
        </div>

        <!-- Ruang Temu / Link Online (Jika Disetujui) -->
        @if($appointment->meeting_link_or_room)
            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-start gap-3">
                <span class="material-symbols-outlined text-primary-600 text-xl mt-0.5">place</span>
                <div>
                    <h4 class="text-xs font-bold text-indigo-900">Lokasi / Tautan Pertemuan:</h4>
                    <p class="text-sm font-semibold text-primary-700 mt-0.5">{{ $appointment->meeting_link_or_room }}</p>
                </div>
            </div>
        @endif

        <!-- Tanggapan / Alasan dari Guru BK -->
        @if($appointment->counselor_response_reason)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 flex items-start gap-3">
                <span class="material-symbols-outlined text-amber-600 text-xl mt-0.5">feedback</span>
                <div>
                    <h4 class="text-xs font-bold text-amber-900">Catatan dari Guru BK:</h4>
                    <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">{{ $appointment->counselor_response_reason }}</p>
                </div>
            </div>
        @endif

        <!-- Ringkasan Cerita Awal Siswa -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Cerita / Kebutuhan Siswa</h3>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $appointment->initial_concern }}
            </div>
        </div>

        <!-- Hasil Sesi (Jika Ada) -->
        @if($appointment->session)
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Hasil Pelaksanaan Bimbingan</h3>
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-800 mb-1">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        Status Kehadiran Siswa: {{ $appointment->session->attendance_status === 'attended' ? 'Hadir di Ruang BK' : 'Tidak Hadir' }}
                    </div>
                    @if($appointment->session->public_summary)
                        <p class="text-xs text-slate-700 mt-2 leading-relaxed">
                            <span class="font-semibold text-slate-800">Catatan Refleksi & Motivasi Guru BK:</span><br>
                            {{ $appointment->session->public_summary }}
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Opsi Pembatalan (Jika Belum Selesai/Batal) -->
        @if(in_array($appointment->status, ['pending', 'approved']))
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    Ada tugas atau ulangan mendadak di kelas? Anda dapat membatalkan permohonan ini agar slot dapat dialihkan untuk siswa lain.
                </div>
                <form action="{{ route('student.appointment.cancel', $appointment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal bimbingan ini?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold border border-rose-200 transition-colors cursor-pointer">
                        Batalkan Jadwal
                    </button>
                </form>
            </div>
        @endif

    </div>

</div>
@endsection
