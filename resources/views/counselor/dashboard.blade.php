@extends('layouts.app')

@section('title', 'Dashboard Guru BK')

@section('content')
<div class="space-y-6">

    <!-- Hero Card -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-teal-700 to-indigo-800 text-white p-6 sm:p-8 shadow-lg shadow-teal-900/10">
        <div class="max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-semibold backdrop-blur-md mb-3">
                Ruang Kerja Guru BK
            </span>
            <h1 class="text-2xl sm:text-3xl font-heading font-bold mb-2">
                Selamat Bertugas, {{ $counselor->user->name }}
            </h1>
            <p class="text-teal-100 text-xs sm:text-sm leading-relaxed mb-6">
                {{ $counselor->specialization }} &bull; {{ $counselor->room_location }}
            </p>
            <div class="flex items-center gap-3">
                <a href="{{ route('counselor.schedules') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white text-teal-800 font-semibold text-xs shadow-md hover:bg-teal-50 transition-all">
                    <span class="material-symbols-outlined text-base">more_time</span>
                    Atur Slot Jadwal BK
                </a>
                <a href="{{ route('counselor.appointments') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white/20 text-white font-semibold text-xs hover:bg-white/30 backdrop-blur-md transition-all">
                    <span class="material-symbols-outlined text-base">assignment</span>
                    Permohonan Siswa
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">notification_important</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['pending_count'] }}</div>
                <div class="text-xs text-slate-500">Permohonan Siswa Baru</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">today</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['today_count'] }}</div>
                <div class="text-xs text-slate-500">Bimbingan Hari Ini</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">verified</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['completed_count'] }}</div>
                <div class="text-xs text-slate-500">Selesai Dibimbing</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['total_clients'] }}</div>
                <div class="text-xs text-slate-500">Total Siswa Binaan</div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Jadwal Hari Ini & Permohonan Baru -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Agenda Hari Ini -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-teal-600">calendar_today</span>
                        <h2 class="font-bold text-slate-900 text-base">Agenda Bimbingan Siswa Hari Ini</h2>
                    </div>
                    <span class="text-xs text-slate-400">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</span>
                </div>

                @if($todayAppointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($todayAppointments as $apt)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-900">{{ $apt->appointment_code }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-100 text-indigo-800">
                                            {{ substr($apt->start_time, 0, 5) }} - {{ substr($apt->end_time, 0, 5) }} WIB
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        {{ $apt->student->user->name ?? 'Siswa' }}
                                        <span class="font-normal text-xs text-slate-500">({{ $apt->student->study_program ?? '' }})</span>
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        Bidang: {{ $apt->service->name ?? '-' }} &bull; Tempat: {{ $apt->method === 'online' ? 'Daring' : 'Ruang BK' }}
                                    </p>
                                </div>
                                <a href="{{ route('counselor.session.view', $apt->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold shadow-xs transition-colors self-start sm:self-center">
                                    <span class="material-symbols-outlined text-base">edit_note</span>
                                    Mulai / Catat Sesi
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                            <span class="material-symbols-outlined">event_available</span>
                        </div>
                        <p class="text-xs text-slate-500">Tidak ada sesi bimbingan terkonfirmasi untuk hari ini.</p>
                    </div>
                @endif
            </div>

            <!-- Permohonan Menunggu Konfirmasi -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600">hourglass_top</span>
                        <h2 class="font-bold text-slate-900 text-base">Permohonan Siswa yang Memerlukan Tindakan</h2>
                    </div>
                    <a href="{{ route('counselor.appointments', ['status' => 'pending']) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">
                        Lihat Semua
                    </a>
                </div>

                @if($pendingRequests->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingRequests as $req)
                            <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-900">{{ $req->appointment_code }}</span>
                                        <span class="text-xs text-slate-500">&bull; {{ \Carbon\Carbon::parse($req->consultation_date)->translatedFormat('d M Y') }} ({{ substr($req->start_time, 0, 5) }} WIB)</span>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800">
                                        {{ $req->student->user->name ?? 'Siswa' }}
                                        <span class="text-xs font-normal text-slate-500">NISN: {{ $req->student->student_number ?? '-' }} &bull; {{ $req->student->study_program ?? '-' }}</span>
                                    </h3>
                                    <p class="text-xs text-slate-600 line-clamp-1 italic">
                                        "{{ $req->initial_concern }}"
                                    </p>
                                </div>
                                <a href="{{ route('counselor.appointment.detail', $req->id) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors self-start sm:self-center">
                                    Tinjau Permohonan
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 py-6 text-center">Tidak ada permohonan siswa yang tertunda.</p>
                @endif
            </div>

        </div>

        <!-- Right Side: Monitoring Tindak Lanjut -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-teal-600">checklist</span>
                    <h3 class="font-bold text-slate-900 text-sm">Pemantauan Tindak Lanjut Siswa</h3>
                </div>

                @if($pendingFollowUps->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingFollowUps as $fu)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                                <div class="font-semibold text-slate-900">{{ $fu->student->user->name ?? 'Siswa' }}</div>
                                <div class="text-slate-500 text-[11px]">{{ $fu->student->study_program ?? '' }}</div>
                                <div class="text-slate-600 line-clamp-2 leading-relaxed">{{ $fu->action_plan }}</div>
                                <div class="flex items-center justify-between text-[11px] pt-1">
                                    <span class="text-slate-400">Tenggat: {{ $fu->due_date ? \Carbon\Carbon::parse($fu->due_date)->format('d/m/Y') : '-' }}</span>
                                    <span class="font-semibold text-amber-700">{{ $fu->status_label }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 py-4 text-center">Semua tindak lanjut siswa telah terselesaikan.</p>
                @endif
            </div>

            <!-- Kode Etik Kerahasiaan BK -->
            <div class="p-5 rounded-3xl bg-teal-50 border border-teal-100 text-teal-900 text-xs">
                <div class="font-bold flex items-center gap-1.5 mb-2">
                    <span class="material-symbols-outlined text-teal-600 text-base">verified_user</span>
                    Kode Etik Guru BK Sekolah
                </div>
                <p class="text-teal-800 leading-relaxed">
                    Sesuai asas kerahasiaan bimbingan dan konseling, catatan psikologis dan pribadi siswa disimpan terpisah dan hanya dapat diakses oleh Guru BK yang bersangkutan.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
