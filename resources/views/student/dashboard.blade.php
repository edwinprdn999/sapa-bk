@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Card -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 to-indigo-700 text-white p-6 sm:p-8 shadow-lg shadow-indigo-500/15">
        <div class="max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-semibold backdrop-blur-md mb-3">
                Portal Siswa
            </span>
            <h1 class="text-2xl sm:text-3xl font-heading font-bold mb-2">
                Halo, {{ $student->user->name }}!
            </h1>
            <p class="text-indigo-100 text-xs sm:text-sm leading-relaxed mb-6">
                Butuh teman cerita atau bimbingan seputar belajar, masa depan karir, atau masalah pribadi? Guru BK siap mendampingi Anda di sekolah dengan aman dan rahasia.
            </p>
            <a href="{{ route('student.appointment.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-primary-700 font-semibold text-xs shadow-md hover:bg-indigo-50 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                Ajukan Bimbingan Baru
            </a>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">calendar_month</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['total'] }}</div>
                <div class="text-xs text-slate-500">Total Bimbingan</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">hourglass_top</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['pending'] }}</div>
                <div class="text-xs text-slate-500">Menunggu Guru BK</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">task_alt</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['approved'] }}</div>
                <div class="text-xs text-slate-500">Jadwal Disetujui</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['completed'] }}</div>
                <div class="text-xs text-slate-500">Selesai Terlaksana</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Jadwal Mendatang & Menunggu Konfirmasi -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-600">event</span>
                        <h2 class="font-bold text-slate-900 text-base">Jadwal Bimbingan Aktif</h2>
                    </div>
                    <a href="{{ route('student.appointments') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">
                        Lihat Semua Riwayat
                    </a>
                </div>

                @if($upcomingAppointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingAppointments as $apt)
                            <div class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-900">{{ $apt->appointment_code }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $apt->status_color_class }}">
                                            {{ $apt->status_label }}
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-800">{{ $apt->service->name ?? 'Layanan BK' }}</h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm text-slate-400">psychology</span>
                                            {{ $apt->counselor->user->name ?? 'Guru BK' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm text-slate-400">calendar_today</span>
                                            {{ \Carbon\Carbon::parse($apt->consultation_date)->translatedFormat('d F Y') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm text-slate-400">schedule</span>
                                            {{ substr($apt->start_time, 0, 5) }} - {{ substr($apt->end_time, 0, 5) }} WIB
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm text-slate-400">
                                                {{ $apt->method === 'online' ? 'videocam' : 'location_on' }}
                                            </span>
                                            {{ $apt->method === 'online' ? 'Daring' : 'Tatap Muka di Ruang BK' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2">
                                    <a href="{{ route('student.appointment.detail', $apt->id) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition-colors shadow-xs">
                                        Detail Sesi
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-2xl">event_busy</span>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Tidak ada jadwal bimbingan aktif saat ini.</p>
                        <p class="text-xs text-slate-400 mt-1">Ingin berkonsultasi? Silakan klik tombol di bawah untuk memilih jadwal dengan Guru BK.</p>
                        <a href="{{ route('student.appointment.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-full bg-primary-600 text-white text-xs font-semibold hover:bg-primary-700 transition-all shadow-xs">
                            <span class="material-symbols-outlined text-sm">add</span>
                            Pilih Jadwal Guru BK
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tindak Lanjut Aktif -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-teal-600">checklist</span>
                        <h2 class="font-bold text-slate-900 text-base">Rencana Tindak Lanjut Siswa (Action Plan)</h2>
                    </div>
                    <a href="{{ route('student.follow_ups') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">
                        Buka Semua
                    </a>
                </div>

                @if($pendingFollowUps->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingFollowUps as $fu)
                            <div class="p-4 rounded-2xl bg-teal-50/50 border border-teal-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="text-xs font-semibold text-teal-800">
                                        Dari Guru BK: {{ $fu->counselor->user->name ?? 'Guru BK' }}
                                    </div>
                                    <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                        {{ $fu->action_plan }}
                                    </p>
                                    @if($fu->due_date)
                                        <div class="text-[11px] text-slate-500">
                                            Batas Waktu: {{ \Carbon\Carbon::parse($fu->due_date)->translatedFormat('d F Y') }}
                                        </div>
                                    @endif
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-semibold self-start sm:self-center">
                                    {{ $fu->status_label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500 py-3 text-center">
                        Belum ada tugas atau rencana tindak lanjut dari sesi bimbingan bersama Guru BK.
                    </p>
                @endif
            </div>
        </div>

        <!-- Right Side: Info Siswa & Notifikasi -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Profil Siswa -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Profil Siswa</h3>
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 font-bold text-lg flex items-center justify-center">
                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 text-sm">{{ $student->user->name }}</div>
                        <div class="text-xs text-slate-500">NISN: {{ $student->student_number }}</div>
                    </div>
                </div>
                <div class="mt-4 space-y-2.5 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kelas & Rombel:</span>
                        <span class="font-semibold text-slate-800">{{ $student->study_program }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Jurusan / Peminatan:</span>
                        <span class="font-semibold text-slate-800">{{ $student->faculty ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tahun Ajaran:</span>
                        <span class="font-semibold text-slate-800">{{ $student->academic_year }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kontak Orang Tua:</span>
                        <span class="font-semibold text-slate-800 text-[11px]">{{ $student->emergency_contact ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Notifikasi Siswa -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary-600">notifications</span>
                    <h3 class="font-bold text-slate-900 text-sm">Pemberitahuan Guru BK</h3>
                </div>

                @if($notifications->count() > 0)
                    <div class="space-y-3">
                        @foreach($notifications as $notif)
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                                <div class="font-semibold text-slate-800 mb-0.5">{{ $notif->title }}</div>
                                <div class="text-slate-500 leading-relaxed">{{ $notif->message }}</div>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 py-3 text-center">Belum ada pemberitahuan baru.</p>
                @endif
            </div>

            <!-- Pesan Pendampingan BK -->
            <div class="p-5 rounded-3xl bg-indigo-50 border border-indigo-100 text-indigo-900 text-xs">
                <div class="font-bold flex items-center gap-1.5 mb-2">
                    <span class="material-symbols-outlined text-indigo-600 text-base">support_agent</span>
                    Ruang Bimbingan & Konseling Sekolah
                </div>
                <p class="text-indigo-700 leading-relaxed mb-2">
                    Guru BK adalah sahabat siswa di sekolah. Jika Anda membutuhkan bimbingan mendesak, silakan langsung kunjungi Ruang BK sekolah pada jam istirahat.
                </p>
                <div class="font-semibold text-indigo-800">Lokasi: Ruang BK Gedung Utama Lt. 1</div>
            </div>
        </div>

    </div>
</div>
@endsection
