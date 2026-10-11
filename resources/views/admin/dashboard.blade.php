@extends('layouts.app')

@section('title', 'Admin Panel & Analitik')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-900">Panel Administrasi & Laporan Agregat</h1>
            <p class="text-xs text-slate-500">Statistik operasional layanan bimbingan konseling tanpa pembocoran data sensitif.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.audit_logs') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors shadow-xs">
                <span class="material-symbols-outlined text-base">security</span>
                Audit Log Sistem
            </a>
        </div>
    </div>

    <!-- Aggregate Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">analytics</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['total_appointments'] }}</div>
                <div class="text-xs text-slate-500">Total Permohonan</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['completed'] }}</div>
                <div class="text-xs text-slate-500">Selesai Dilayani</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">school</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['students_count'] }}</div>
                <div class="text-xs text-slate-500">Siswa Terdaftar</div>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">psychology</span>
            </div>
            <div>
                <div class="text-2xl font-bold font-heading text-slate-800">{{ $stats['counselors_count'] }}</div>
                <div class="text-xs text-slate-500">Guru BK Aktif</div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown (Aggregate) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-900 text-sm mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-600">pie_chart</span>
                Distribusi Permohonan per Kategori Layanan
            </h2>

            <div class="space-y-4">
                @foreach($serviceBreakdown as $srv)
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-800">{{ $srv->name }}</span>
                            <span class="text-primary-600">{{ $srv->appointments_count }} Sesi</span>
                        </div>
                        @php
                            $percentage = $stats['total_appointments'] > 0 ? round(($srv->appointments_count / $stats['total_appointments']) * 100) : 0;
                        @endphp
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-600">history</span>
                    Audit Log Keamanan Terbaru
                </h3>
                <a href="{{ route('admin.audit_logs') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800">Semua</a>
            </div>

            <div class="space-y-2.5">
                @foreach($recentAudits as $log)
                    <div class="p-3 rounded-xl bg-slate-50 text-xs flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-slate-800">{{ $log->action }}</span>
                            <div class="text-[11px] text-slate-400">{{ $log->entity_type }} #{{ $log->entity_id }} &bull; IP: {{ $log->ip_address }}</div>
                        </div>
                        <span class="text-[10px] text-slate-400">{{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->diffForHumans() : '-' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Permohonan Terbaru (Metadata Operasional) -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
        <h3 class="font-bold text-slate-900 text-sm mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-600">list_alt</span>
            Daftar Aktivitas Permohonan Terbaru (Operasional Agregat)
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase font-semibold">
                        <th class="p-3">Kode</th>
                        <th class="p-3">Siswa</th>
                        <th class="p-3">Guru BK</th>
                        <th class="p-3">Layanan</th>
                        <th class="p-3">Jadwal</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentAppointments as $apt)
                        <tr class="hover:bg-slate-50/50">
                            <td class="p-3 font-mono font-bold text-slate-900">{{ $apt->appointment_code }}</td>
                            <td class="p-3 text-slate-800">{{ $apt->student->user->name ?? '-' }}</td>
                            <td class="p-3 text-slate-800">{{ $apt->counselor->user->name ?? '-' }}</td>
                            <td class="p-3 text-slate-600">{{ $apt->service->name ?? '-' }}</td>
                            <td class="p-3 text-slate-600">{{ \Carbon\Carbon::parse($apt->consultation_date)->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $apt->status_color_class }}">
                                    {{ $apt->status_label }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
