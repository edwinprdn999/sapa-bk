@extends('layouts.app')

@section('title', 'Riwayat Jadwal & Permohonan BK')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Jadwal & Riwayat Bimbingan Siswa</h1>
            <p class="text-xs text-slate-500">Daftar seluruh riwayat permohonan bimbingan konseling Anda bersama Guru BK.</p>
        </div>
        <a href="{{ route('student.appointment.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-primary-600 text-white text-xs font-semibold hover:bg-primary-700 shadow-sm transition-all self-start sm:self-auto">
            <span class="material-symbols-outlined text-base">add</span>
            Ajukan Bimbingan Baru
        </a>
    </div>

    <!-- Filter Status Chips -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <a href="{{ route('student.appointments') }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ empty($status) ? 'bg-primary-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua
        </a>
        <a href="{{ route('student.appointments', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'pending' ? 'bg-amber-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Menunggu Guru BK
        </a>
        <a href="{{ route('student.appointments', ['status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'approved' ? 'bg-emerald-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Disetujui
        </a>
        <a href="{{ route('student.appointments', ['status' => 'completed']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'completed' ? 'bg-indigo-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Selesai
        </a>
        <a href="{{ route('student.appointments', ['status' => 'rejected']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'rejected' ? 'bg-rose-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Ditolak
        </a>
        <a href="{{ route('student.appointments', ['status' => 'cancelled']) }}" class="px-3.5 py-1.5 rounded-full text-xs font-medium transition-colors {{ $status === 'cancelled' ? 'bg-slate-600 text-white font-semibold shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Appointments Table/Cards -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        @if($appointments->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($appointments as $apt)
                    <div class="p-5 hover:bg-slate-50/60 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1.5 max-w-xl">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-900">{{ $apt->appointment_code }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $apt->status_color_class }}">
                                    {{ $apt->status_label }}
                                </span>
                            </div>

                            <h3 class="font-bold text-slate-800 text-sm">
                                {{ $apt->service->name ?? 'Layanan Bimbingan' }}
                            </h3>

                            <p class="text-xs text-slate-500 line-clamp-1">
                                {{ $apt->initial_concern }}
                            </p>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 pt-1">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-slate-400">psychology</span>
                                    {{ $apt->counselor->user->name ?? 'Guru BK' }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-slate-400">calendar_month</span>
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

                        <div class="flex items-center gap-2 self-end sm:self-center">
                            <a href="{{ route('student.appointment.detail', $apt->id) }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-primary-50 text-primary-700 hover:bg-primary-100 text-xs font-semibold transition-colors">
                                Lihat Detail
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">search_off</span>
                </div>
                <p class="text-sm font-medium text-slate-700">Tidak ada data bimbingan pada kategori ini.</p>
                <p class="text-xs text-slate-400 mt-1">Gunakan filter status lain atau ajukan permohonan bimbingan baru ke Guru BK.</p>
            </div>
        @endif
    </div>

</div>
@endsection
